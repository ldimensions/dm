<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Models\ReligionTmp;
use App\Http\Models\RestaurantTemp;
use App\Http\Models\GroceryTmp;

class DashboardController extends Controller
{
    public function __construct(){
        $this->middleware('role:Admin');
    }

    public function dashboard(){

        $religionSubmitted                  =   0;
        $religionRejected                   =   0;
        $restaurantSubmitted                =   0;
        $restaurantRejected                 =   0;
        $grocerySubmitted                   =   0;
        $groceryRejected                    =   0;
        
        $religionSubmitted                  =   ReligionTmp::select('religion_tmp.id')                                                                                                                               
                                                    ->where('religion_tmp.status', '=', '2')->get()->count();

        $religionRejected                   =   ReligionTmp::select('religion_tmp.id')                                                                                                                               
                                                    ->where('religion_tmp.status', '=', '4')->get()->count();     
                                                    
        $restaurantSubmitted                =   RestaurantTemp::select('restaurant_tmp.id')                                                                                                                               
                                                    ->where('restaurant_tmp.status', '=', '2')->get()->count();

        $restaurantRejected                 =   RestaurantTemp::select('restaurant_tmp.id')                                                                                                                               
                                                    ->where('restaurant_tmp.status', '=', '4')->get()->count();                                                     

        $grocerySubmitted                   =   GroceryTmp::select('grocery_tmp.id')                                                                                                                               
                                                    ->where('grocery_tmp.status', '=', '2')->get()->count();

        $groceryRejected                    =   GroceryTmp::select('grocery_tmp.id')                                                                                                                               
                                                    ->where('grocery_tmp.status', '=', '4')->get()->count();                                                      

        return view('admin.dashboard',['religionSubmitted' => $religionSubmitted, 
                                        'religionRejected' => $religionRejected,
                                        'restaurantSubmitted' => $restaurantSubmitted,
                                        'restaurantRejected' => $restaurantRejected,
                                        'grocerySubmitted' => $grocerySubmitted,
                                        'groceryRejected' => $groceryRejected
                                        ]);
    }  
    
    public function dbBackUp(){
        
        $name                               =   env("DB_DATABASE", ""); 
        $host                               =   env("DB_HOST", ""); 
        $user                               =   env("DB_USERNAME", ""); 
        $pass                               =   env("DB_PASSWORD", ""); 

        $link                               =   mysql_connect($host,$user,$pass) or die('error');
        mysql_select_db($name,$link);
        $tables                             =   array();
        $result                             =   mysql_query('SHOW TABLES');
		while($row = mysql_fetch_row($result))
		{
			$tables[] = $row[0];
        }

        foreach($tables as $table)
        {
            $result = mysql_query('SELECT * FROM '.$table);
            $num_fields = mysql_num_fields($result);
            
            $return.= 'DROP TABLE '.$table.';';
            $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
            $return.= "\n\n".$row2[1].";\n\n";
            
            for ($i = 0; $i < $num_fields; $i++) 
            {
                while($row = mysql_fetch_row($result))
                {
                    $return.= 'INSERT INTO '.$table.' VALUES(';
                    for($j=0; $j < $num_fields; $j++) 
                    {
                        $row[$j] = addslashes($row[$j]);
                        $row[$j] = ereg_replace("\n","\\n",$row[$j]);
                        if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
                        if ($j < ($num_fields-1)) { $return.= ','; }
                    }
                    $return.= ");\n";
                }
            }
            $return.="\n\n\n";
        }
        
        //save file
        $handle = fopen('db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
        fwrite($handle,$return);
        fclose($handle);
            
        
    }

}

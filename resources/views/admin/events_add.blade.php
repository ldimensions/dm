@extends('layouts.admin')

@section('content')
  <div id="page-wrapper">
    <div style="position: relative;height:30px;"></div>
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if($event['id']) {{ 'Edit Event' }} @else {{ 'Add Event' }} @endif
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="panel-body">                                          
                            <form name="event" action="{{ url('/admin/event_add') }}" method="POST" role="form" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{ $event['id'] }}" id="id">
                                <input type="hidden" name="addressId" value="{{ $event['addressId'] }}" id="addressId">
                                <input type="hidden" name="urlId" value="{{ $event['urlId'] }}" id="urlId">
                                <input type="hidden" name="seoId" value="{{ $event['seoId'] }}" id="urlId">  
                                
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#event" data-toggle="tab">Event</a>
                                    </li>
                                    <li><a href="#address" data-toggle="tab">Address</a>
                                    </li>
                                    <li><a href="#meta" data-toggle="tab">Meta</a>
                                    </li>
                                    <li><a href="#images" data-toggle="tab">Images</a>
                                    </li> 
                                </ul>  
                                <div class="tab-content">
                                    <div class="tab-pane fade in active" id="event" style="position: relative;min-height: 600px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                                <label>Name</label>
                                                <input name="name" value="{{ old('name', $event['name']) }}" id="name" maxlength="100" class="form-control">
                                                <small class="text-danger">{{ $errors->first('name') }}</small>
                                            </div>
                                            <div class="form-group{{ $errors->has('urlName') ? ' has-error' : '' }}">
                                                <label>URL</label>
                                                <input name="urlName" value="{{ old('urlName', $event['urlName']) }}" id="urlName" maxlength="180" class="form-control" >
                                                <input type="hidden" name="urlNameChk" value="{{ $event['urlName'] }}" id="urlNameChk" class="form-control" >
                                                <small class="text-danger">{{ $errors->first('urlName') }}</small>                                            
                                            </div> 
                                            <div class="form-group">
                                                <label>Website</label>
                                                <input name="website" value="{{ old('website', $event['website']) }}" id="website" maxlength="50" class="form-control">
                                            </div>    
                                           
                                            <div class="form-group">
                                                <label>Premium</label>
                                                <select name="premium" id="premium" class="form-control">
                                                    <option value="0" @if(old('premium', $event['premium']) == 0) {{ 'selected' }} @endif >No</option>
                                                    <option value="1" @if(old('premium', $event['premium']) == 1) {{ 'selected' }} @endif >Yes</option>
                                                </select>
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Order</label>
                                                <input name="order" value="{{ old('order', $event['order']) }}" id="order" maxlength="15" class="form-control">
                                            </div>                                 
                                            <div class="form-group">
                                                <label>Is Disabled</label>
                                                <select name="is_disabled" id="is_disabled" class="form-control">
                                                    <option value="0" @if(old('is_disabled', $event['is_disabled']) == 0) {{ 'selected' }} @endif >No</option>
                                                    <option value="1" @if(old('is_disabled', $event['is_disabled']) == 1) {{ 'selected' }} @endif >Yes</option>
                                                </select>
                                            </div>                                                                                    
                                        </div>
                                        <div class="col-lg-6">  
                                            <div class="form-group">
                                                <label>Description</label>
                                                <textarea name="description" class="form-control" rows="5">{{ old('description', $event['description']) }}</textarea>
                                            </div>                                            
                                            <div class="form-group">
                                                <label>Working Time</label><br/>
                                                <div class='col-md-5'>
                                                    <div class="form-group">
                                                        <div class='input-group date' id='datetimepicker6'>
                                                            <input type='text' class="form-control" />
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class='col-md-5'>
                                                    <div class="form-group">
                                                        <div class='input-group date' id='datetimepicker7'>
                                                            <input type='text' class="form-control" />
                                                            <span class="input-group-addon">
                                                                <span class="glyphicon glyphicon-calendar"></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>                                             
                                            </div>   
                                        </div>                                     
                                    </div>
                                    <div class="tab-pane fade" id="address" style="position: relative;min-height: 455px;" >
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group{{ $errors->has('address1') ? ' has-error' : '' }}">
                                                <label>Address1</label>
                                                <input name="address1" value="{{ old('address1', $event['address1']) }}" id="address1" maxlength="150" class="form-control" >
                                                <small class="text-danger">{{ $errors->first('address1') }}</small>                                            
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Address2</label>
                                                <input name="address2" value="{{ old('address2', $event['address2']) }}" id="address2" maxlength="80" class="form-control">
                                            </div>                                          
                                            <div class="form-group">
                                                <label>City</label>
                                                <select name="city" value="{{ old('city', $event['city']) }}" id="city" class="form-control">
                                                    @foreach ($cities as $key => $city)
                                                        <option 
                                                            value="{{$city['cityId']}}"
                                                            @if(old('city', $event['city']) == $city['cityId']) {{ 'selected' }} @endif
                                                            >
                                                            {{$city['city']}}
                                                        </option>
                                                    @endforeach                                                
                                                </select>
                                            </div>                                         
                                            <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                                                <label>State</label>
                                                <input name="state" value="{{ old('state', $event['state']) }}" id="state" maxlength="40" class="form-control" >
                                                <small class="text-danger">{{ $errors->first('state') }}</small>                                            
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Zip</label>
                                                <input name="zip" value="{{ old('zip', $event['zip']) }}" id="zip" maxlength="10" class="form-control" >
                                            </div>   
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>County</label>
                                                <input name="county" value="{{ old('county', $event['county']) }}" id="county" maxlength="10" class="form-control" >
                                            </div>                                                                                  
                                            <div class="form-group">
                                                <label>Phone1</label>
                                                <input name="phone1" value="{{ old('phone1', $event['phone1']) }}" id="phone1" maxlength="15" class="form-control">
                                            </div>                                          
                                            <div class="form-group">
                                                <label>phone2</label>
                                                <input name="phone2" value="{{ old('phone2', $event['phone2']) }}" id="phone2" maxlength="15" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>Latitude</label>
                                                <input name="latitude" value="{{ old('latitude', $event['latitude']) }}" maxlength="20" id="latitude" class="form-control">
                                            </div>                                          
                                            <div class="form-group">
                                                <label>Longitude</label>
                                                <input name="longitude" value="{{ old('longitude', $event['longitude']) }}" maxlength="20" id="longitude" class="form-control">
                                            </div>                                            
                                        </div>                                        
                                    </div>    
                                    <div class="tab-pane fade" id="meta" style="position: relative;min-height: 520px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>SEOMetaTitle</label>
                                                <input name="SEOMetaTitle" value="{{ old('SEOMetaTitle', $event['SEOMetaTitle']) }}" id="SEOMetaTitle" maxlength="20" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaDesc</label>
                                                <textarea name="SEOMetaDesc" class="form-control" rows="5">{{ old('SEOMetaDesc', $event['SEOMetaDesc']) }}</textarea>                                                
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaPublishedTime</label>
                                                <input name="SEOMetaPublishedTime" value="{{ old('SEOMetaPublishedTime', $event['SEOMetaPublishedTime']) }}" id="SEOMetaPublishedTime" maxlength="15" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>SEOMetaKeywords</label>
                                                <input name="SEOMetaKeywords" value="{{ old('SEOMetaKeywords', $event['SEOMetaKeywords']) }}" id="SEOMetaKeywords" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphTitle</label>
                                                <input name="OpenGraphTitle" value="{{ old('OpenGraphTitle', $event['OpenGraphTitle']) }}" id="OpenGraphTitle" maxlength="20" class="form-control">
                                            </div>                                         
                                        </div>
                                        <div class="col-lg-6">  
                                            <div class="form-group">
                                                <label>OpenGraphDesc</label>
                                                <input name="OpenGraphDesc" value="{{ old('OpenGraphDesc', $event['OpenGraphDesc']) }}" id="OpenGraphDesc" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphUrl</label>
                                                <input name="OpenGraphUrl" value="{{ old('OpenGraphUrl', $event['OpenGraphUrl']) }}" id="OpenGraphUrl" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyType</label>
                                                <input name="OpenGraphPropertyType" value="{{ old('OpenGraphPropertyType', $event['OpenGraphPropertyType']) }}" id="OpenGraphPropertyType" maxlength="25" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyLocale</label>
                                                <input name="OpenGraphPropertyLocale" value="{{ old('OpenGraphPropertyLocale', $event['OpenGraphPropertyLocale']) }}" id="OpenGraphPropertyLocale" maxlength="150" class="form-control">
                                            </div> 
                                            <div class="form-group">
                                                <label>OpenGraphPropertyLocaleAlternate</label>
                                                <input name="OpenGraphPropertyLocaleAlternate" value="{{ old('OpenGraphPropertyLocaleAlternate', $event['OpenGraphPropertyLocaleAlternate']) }}" id="OpenGraphPropertyLocaleAlternate" maxlength="150" class="form-control">
                                            </div>   
                                            <div class="form-group">
                                                <label>OpenGraph</label>
                                                <input name="OpenGraph" value="{{ old('OpenGraph', $event['OpenGraph']) }}" id="OpenGraph" maxlength="150" class="form-control">
                                            </div>                                                                                                                                                                                                                                                                                                                                                                                                     
                                        </div>                                         
                                    </div> 
                                    <div class="tab-pane fade" id="images" style="position: relative;min-height: 210px;">
                                        <br/><br/>
                                        <div class="col-lg-6">
                                            <div class="form-group{{ $errors->has('thumbnail') ? ' has-error' : '' }}">
                                                <label>Thumbnail</label>
                                                <input type="file" class="form-control" name="thumbnail[]" />
                                                <small class="text-danger">{{ $errors->first('thumbnail') }}</small>
                                            </div> 
                                            <div class="form-group{{ $errors->has('photos.*') ? ' has-error' : '' }}">
                                                <label>Main Images</label>
                                                <input type="file" class="form-control" name="photos[]" multiple />
                                                <small class="text-danger">{{ $errors->first('photos.*') }}</small>                                                
                                            </div>                                                                                   
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>Thumbnail</label>
                                                @foreach ($photos as $key => $photo)
                                                    @if($photo['is_primary'] == 1)
                                                        <div class="smallImage">
                                                            <img src="{{ URL::to('/') }}/image/event/{{$event['id']}}/{{$photo['photoName']}}"  style="width:100px;height:100px">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div> 
                                            
                                            <div class="form-group">
                                                <div>Featured</div>
                                                @foreach ($photos as $key => $photo)
                                                    @if($photo['is_primary'] == 0)
                                                        <div class="smallImage" style="float:left;padding:10px;">
                                                            <img src="{{ URL::to('/') }}/image/event/{{$event['id']}}/{{$photo['photoName']}}"  style="width:100px;height:100px">
                                                        </div>                                                    
                                                    @endif
                                                @endforeach
                                            </div>                                                                                                 
                                        </div>
                                    </div>                             
                                </div>
                                <div style="position:relative;widht:100%">                                  
                                    <button type="submit" class="btn btn-default">Submit</button>
                                    <a href="{{ url('/admin/event') }}"><button type="button" class="btn btn-default">Cancel</button></a>                                                              
                                </div>
                            </form>
                        </div>
                        <div class="bootstrap-datetimepicker-widget dropdown-menu-datetimepicker bottom pull-right" style="top: 325px; bottom: auto; left: auto; right: 0px;"><ul class="list-unstyled"><li class="collapse in"><div class="datepicker"><div class="datepicker-days" style=""><table class="table-condensed"><thead><tr><th class="prev" data-action="previous"><span class="glyphicon glyphicon-chevron-left" title="Previous Month"></span></th><th class="picker-switch" data-action="pickerSwitch" colspan="5" title="Select Month">August 2018</th><th class="next" data-action="next"><span class="glyphicon glyphicon-chevron-right" title="Next Month"></span></th></tr><tr><th class="dow">Su</th><th class="dow">Mo</th><th class="dow">Tu</th><th class="dow">We</th><th class="dow">Th</th><th class="dow">Fr</th><th class="dow">Sa</th></tr></thead><tbody><tr><td data-action="selectDay" data-day="07/29/2018" class="day old weekend">29</td><td data-action="selectDay" data-day="07/30/2018" class="day old">30</td><td data-action="selectDay" data-day="07/31/2018" class="day old">31</td><td data-action="selectDay" data-day="08/01/2018" class="day">1</td><td data-action="selectDay" data-day="08/02/2018" class="day">2</td><td data-action="selectDay" data-day="08/03/2018" class="day">3</td><td data-action="selectDay" data-day="08/04/2018" class="day weekend">4</td></tr><tr><td data-action="selectDay" data-day="08/05/2018" class="day weekend">5</td><td data-action="selectDay" data-day="08/06/2018" class="day">6</td><td data-action="selectDay" data-day="08/07/2018" class="day">7</td><td data-action="selectDay" data-day="08/08/2018" class="day">8</td><td data-action="selectDay" data-day="08/09/2018" class="day">9</td><td data-action="selectDay" data-day="08/10/2018" class="day">10</td><td data-action="selectDay" data-day="08/11/2018" class="day weekend">11</td></tr><tr><td data-action="selectDay" data-day="08/12/2018" class="day weekend">12</td><td data-action="selectDay" data-day="08/13/2018" class="day">13</td><td data-action="selectDay" data-day="08/14/2018" class="day">14</td><td data-action="selectDay" data-day="08/15/2018" class="day">15</td><td data-action="selectDay" data-day="08/16/2018" class="day">16</td><td data-action="selectDay" data-day="08/17/2018" class="day">17</td><td data-action="selectDay" data-day="08/18/2018" class="day weekend">18</td></tr><tr><td data-action="selectDay" data-day="08/19/2018" class="day weekend">19</td><td data-action="selectDay" data-day="08/20/2018" class="day">20</td><td data-action="selectDay" data-day="08/21/2018" class="day">21</td><td data-action="selectDay" data-day="08/22/2018" class="day">22</td><td data-action="selectDay" data-day="08/23/2018" class="day active today">23</td><td data-action="selectDay" data-day="08/24/2018" class="day">24</td><td data-action="selectDay" data-day="08/25/2018" class="day weekend">25</td></tr><tr><td data-action="selectDay" data-day="08/26/2018" class="day weekend">26</td><td data-action="selectDay" data-day="08/27/2018" class="day">27</td><td data-action="selectDay" data-day="08/28/2018" class="day">28</td><td data-action="selectDay" data-day="08/29/2018" class="day">29</td><td data-action="selectDay" data-day="08/30/2018" class="day">30</td><td data-action="selectDay" data-day="08/31/2018" class="day">31</td><td data-action="selectDay" data-day="09/01/2018" class="day new weekend">1</td></tr><tr><td data-action="selectDay" data-day="09/02/2018" class="day new weekend">2</td><td data-action="selectDay" data-day="09/03/2018" class="day new">3</td><td data-action="selectDay" data-day="09/04/2018" class="day new">4</td><td data-action="selectDay" data-day="09/05/2018" class="day new">5</td><td data-action="selectDay" data-day="09/06/2018" class="day new">6</td><td data-action="selectDay" data-day="09/07/2018" class="day new">7</td><td data-action="selectDay" data-day="09/08/2018" class="day new weekend">8</td></tr></tbody></table></div><div class="datepicker-months" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev" data-action="previous"><span class="glyphicon glyphicon-chevron-left" title="Previous Year"></span></th><th class="picker-switch" data-action="pickerSwitch" colspan="5" title="Select Year">2018</th><th class="next" data-action="next"><span class="glyphicon glyphicon-chevron-right" title="Next Year"></span></th></tr></thead><tbody><tr><td colspan="7"><span data-action="selectMonth" class="month">Jan</span><span data-action="selectMonth" class="month">Feb</span><span data-action="selectMonth" class="month">Mar</span><span data-action="selectMonth" class="month">Apr</span><span data-action="selectMonth" class="month">May</span><span data-action="selectMonth" class="month">Jun</span><span data-action="selectMonth" class="month">Jul</span><span data-action="selectMonth" class="month active">Aug</span><span data-action="selectMonth" class="month">Sep</span><span data-action="selectMonth" class="month">Oct</span><span data-action="selectMonth" class="month">Nov</span><span data-action="selectMonth" class="month">Dec</span></td></tr></tbody></table></div><div class="datepicker-years" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev" data-action="previous"><span class="glyphicon glyphicon-chevron-left" title="Next Decade"></span></th><th class="picker-switch" data-action="pickerSwitch" colspan="5" title="Select Decade">2013-2024</th><th class="next" data-action="next"><span class="glyphicon glyphicon-chevron-right" title="Previous Decade"></span></th></tr></thead><tbody><tr><td colspan="7"><span data-action="selectYear" class="year">2013</span><span data-action="selectYear" class="year">2014</span><span data-action="selectYear" class="year">2015</span><span data-action="selectYear" class="year">2016</span><span data-action="selectYear" class="year">2017</span><span data-action="selectYear" class="year active">2018</span><span data-action="selectYear" class="year">2019</span><span data-action="selectYear" class="year">2020</span><span data-action="selectYear" class="year">2021</span><span data-action="selectYear" class="year">2022</span><span data-action="selectYear" class="year">2023</span><span data-action="selectYear" class="year">2024</span></td></tr></tbody></table></div><div class="datepicker-decades" style="display: none;"><table class="table-condensed"><thead><tr><th class="prev" data-action="previous"><span class="glyphicon glyphicon-chevron-left" title="Previous Century"></span></th><th class="picker-switch" data-action="pickerSwitch" colspan="5">1999-2099</th><th class="next" data-action="next"><span class="glyphicon glyphicon-chevron-right" title="Next Century"></span></th></tr></thead><tbody><tr><td colspan="7"><span data-action="selectDecade" class="decade" data-selection="2005">2000 - 2011</span><span data-action="selectDecade" class="decade" data-selection="2017">2012 - 2023</span><span data-action="selectDecade" class="decade" data-selection="2029">2024 - 2035</span><span data-action="selectDecade" class="decade" data-selection="2041">2036 - 2047</span><span data-action="selectDecade" class="decade" data-selection="2053">2048 - 2059</span><span data-action="selectDecade" class="decade" data-selection="2065">2060 - 2071</span><span data-action="selectDecade" class="decade" data-selection="2077">2072 - 2083</span><span data-action="selectDecade" class="decade" data-selection="2089">2084 - 2095</span><span data-action="selectDecade" class="decade" data-selection="2101">2096 - 2107</span><span></span><span></span><span></span></td></tr></tbody></table></div></div></li><li class="picker-switch accordion-toggle"><table class="table-condensed"><tbody><tr><td><a data-action="togglePicker" title="Select Time"><span class="glyphicon glyphicon-time"></span></a></td></tr></tbody></table></li><li class="collapse"><div class="timepicker"><div class="timepicker-picker"><table class="table-condensed"><tr><td><a href="#" tabindex="-1" title="Increment Hour" class="btn" data-action="incrementHours"><span class="glyphicon glyphicon-chevron-up"></span></a></td><td class="separator"></td><td><a href="#" tabindex="-1" title="Increment Minute" class="btn" data-action="incrementMinutes"><span class="glyphicon glyphicon-chevron-up"></span></a></td><td class="separator"></td></tr><tr><td><span class="timepicker-hour" data-time-component="hours" title="Pick Hour" data-action="showHours">12</span></td><td class="separator">:</td><td><span class="timepicker-minute" data-time-component="minutes" title="Pick Minute" data-action="showMinutes">15</span></td><td><button class="btn btn-primary" data-action="togglePeriod" tabindex="-1" title="Toggle Period">AM</button></td></tr><tr><td><a href="#" tabindex="-1" title="Decrement Hour" class="btn" data-action="decrementHours"><span class="glyphicon glyphicon-chevron-down"></span></a></td><td class="separator"></td><td><a href="#" tabindex="-1" title="Decrement Minute" class="btn" data-action="decrementMinutes"><span class="glyphicon glyphicon-chevron-down"></span></a></td><td class="separator"></td></tr></table></div><div class="timepicker-hours" style="display: none;"><table class="table-condensed"><tr><td data-action="selectHour" class="hour">12</td><td data-action="selectHour" class="hour">01</td><td data-action="selectHour" class="hour">02</td><td data-action="selectHour" class="hour">03</td></tr><tr><td data-action="selectHour" class="hour">04</td><td data-action="selectHour" class="hour">05</td><td data-action="selectHour" class="hour">06</td><td data-action="selectHour" class="hour">07</td></tr><tr><td data-action="selectHour" class="hour">08</td><td data-action="selectHour" class="hour">09</td><td data-action="selectHour" class="hour">10</td><td data-action="selectHour" class="hour">11</td></tr></table></div><div class="timepicker-minutes" style="display: none;"><table class="table-condensed"><tr><td data-action="selectMinute" class="minute">00</td><td data-action="selectMinute" class="minute">05</td><td data-action="selectMinute" class="minute">10</td><td data-action="selectMinute" class="minute">15</td></tr><tr><td data-action="selectMinute" class="minute">20</td><td data-action="selectMinute" class="minute">25</td><td data-action="selectMinute" class="minute">30</td><td data-action="selectMinute" class="minute">35</td></tr><tr><td data-action="selectMinute" class="minute">40</td><td data-action="selectMinute" class="minute">45</td><td data-action="selectMinute" class="minute">50</td><td data-action="selectMinute" class="minute">55</td></tr></table></div></div></li></ul></div>
                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
            <!-- /.col-lg-12 -->
    </div>
    
  </div>
  <script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
        $('#datetimepicker6').datetimepicker({
            format: "yyyy-mm-dd - hh:ii",
            autoclose: true,
            todayBtn: true,
            //startDate: "2013-02-14 10:00",
            minuteStep: 10
        });
        $('#datetimepicker7').datetimepicker({
            format: "yyyy-mm-dd - hh:ii",
            autoclose: true,
            todayBtn: true,
            //startDate: "2013-02-14 10:00",
            minuteStep: 10
        });
       
    });  
       
    </script>
@endsection
  


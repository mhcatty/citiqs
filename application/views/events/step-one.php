<main class="my-form">
    <div class="cotainer">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div style="background: none;" class="card p-4">

                    <div class="card-body">
                        <form name="events-form" class="needs-validation"
                            action="<?php echo base_url(); ?>events/save_event" method="POST"
                            enctype="multipart/form-data" novalidate>
                            <div class="form-group row">
                                <label for="full_name" class="col-md-4 col-form-label text-md-left">
                                    <h3>
                                        <strong>Create event</strong>
                                    </h3>
                                </label>
                                <div class="col-md-6">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="event-name" class="col-md-4 col-form-label text-md-left">Event Name</label>
                                <div class="col-md-8">

                                    <input type="text" id="event-name" class="input-w border-50 form-control"
                                        name="eventname" required>

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email_address"
                                    class="col-md-4 col-form-label text-md-left">Description</label>
                                <div class="col-md-8 description">
                                    <div id="editor"></div>
                                    <div id="log"></div>
                                    <input id="eventdescript" type="hidden" name="eventdescript">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="image" class="col-md-4 col-form-label text-md-left">Upload Event Image</label>
                                <div class="col-md-8">


                                    <label class="file">
                                        <input type="file" class="border-50" name="userfile" id="file"
                                            onchange="imageUpload(this)" aria-label="File browser">
                                        <span id="imageUpload" class="file-custom" data-content="Choose image ..."></span>
                                    </label>
                                    <div style="padding-left: 0;" class="col-sm-6">
                                        <img src="<?php echo base_url(); ?>assets/images/img-preview.png" id="preview"
                                            class="img-thumbnail">
                                    </div>


                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="image" class="col-md-4 col-form-label text-md-left">Upload Background Image</label>
                                <div class="col-md-8">


                                    <label class="file">
                                        <input type="file" class="border-50" name="backgroundfile" id="background-file"
                                            onchange="imageBackgroundUpload(this)" aria-label="File browser">
                                        <span class="file-custom" id="img-background" data-content="Choose image ..."></span>
                                    </label>
                                    <div style="padding-left: 0;" class="col-sm-6">
                                        <img src="<?php echo base_url(); ?>assets/images/img-preview.png" id="background-preview"
                                            class="img-thumbnail">
                                    </div>


                                </div>
                            </div>


                            <hr class="w-100 mt-5 mb-5">

                            <!--
                            <div class="form-group row">
                                <label for="shop-type" class="col-md-4 col-form-label text-md-left">Shop type</label>
                                <div class="col-md-4">
                                    <select id="shop-type" class="form-control input-w border-50">
                                        <option>Select option</option>
                                        <option>Option 1</option>
                                        <option>Option 2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="age" class="col-md-4 col-form-label text-md-left">Minimal
                                    Age</label>
                                <div class="col-md-4">
                                    <div class="w-100 age">
                                        <input id="age" type="number" name="age" class="form-control input-w border-50" min="1" max="100"
                                            step="22" value="1">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="free-event" class="col-md-4 col-form-label text-md-left">Free
                                    event</label>
                                <div class="col-md-4">
                                    <select id="free-event" class="form-control input-w border-50">
                                        <option>Select option</option>
                                        <option>Option 1</option>
                                        <option>Option 2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="corona-questions" class="col-md-4 col-form-label text-md-left">Corona
                                    questions <i id="corona-info" style="font-size: 16px;"
                                        class="fa fa-info-circle ml-1"></i></label>
                                <div class="col-md-4">
                                    <select id="corona-questions" class="form-control input-w border-50">
                                        <option>Select option</option>
                                        <option>Option 1</option>
                                        <option>Option 2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="reservation-shop" class="col-md-4 col-form-label text-md-left">Reservation
                                    shop <i id="reservation-info" style="font-size: 16px;"
                                        class="fa fa-info-circle ml-1"></i>
                                </label>
                                <div class="col-md-4">
                                    <select id="reservation-shop" class="form-control input-w border-50">
                                        <option>Select option</option>
                                        <option>Option 1</option>
                                        <option>Option 2</option>
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="event-type" class="col-md-4 col-form-label text-md-left">Event
                                    type(s)</label>
                                <div class="col-md-6">
                                    <input type="text" id="event-type" class="form-control input-w border-50" name="event-type"
                                        placeholder="Type or select an event type">
                                </div>
                            </div>

                            <hr class="w-100 mt-5 mb-5">

                            -->

                            <div class="form-group row wideField">
                                <label for="venue" class="col-md-4 col-form-label text-md-left">Venue</label>
                                <div id="locationField" class="col-md-6">
                                    <input type="text" class="form-control input-w border-50" name="eventVenue"
                                        placeholder="Enter a location" required>
                                </div>
                            </div>

                            <div class="form-group row wideField">
                                <label for="address" class="col-md-4 col-form-label text-md-left">Address</label>
                                <div class="col-md-6">
                                    <input type="text" id="autocomplete" onFocus="geolocate()"
                                        class="field form-control input-w border-50" name="eventAddress" placeholder=""
                                        required>
                                </div>
                            </div>

                            <div class="form-group row wideField">
                                <label for="city" class="col-md-4 col-form-label text-md-left">City</label>
                                <div class="col-md-6">
                                    <input type="text" id="locality" class="field form-control input-w border-50"
                                        name="eventCity" required>
                                </div>
                            </div>

                            <div class="form-group row wideField">
                                <label for="postal-code" class="col-md-4 col-form-label text-md-left">Postal
                                    code</label>
                                <div class="col-md-6">
                                    <input type="text" id="postal_code" class="field form-control input-w border-50"
                                        name="eventZipcode" required>
                                </div>
                            </div>

                            <div class="form-group row wideField">
                                <label for="country" class="col-md-4 col-form-label text-md-left">Country
                                </label>
                                <div class="col-md-6">
                                    <select id="country" class="form-control input-w border-50 field" required>
                                        <option value="">Select option</option>
                                        <?php if(count($countries) > 0): ?>
                                        <?php foreach($countries as $country): ?>
                                        <option value="<?php echo $country; ?>"><?php echo $country; ?></option>
                                        <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <input type="hidden" id="eventCountry" name="eventCountry">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="event-date1" class="col-md-4 col-form-label text-md-left">Time of event
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group date">
                                        <input type="text" class="form-control input-w inp-group-radius-left input-date"
                                            id="event-date1" name="StartDate" required>
                                        <input type="time" class="form-control input-w" id="event-time1"
                                            name="StartTime" value="00:00:00" required>
                                        <span class="input-group-addon fa-input pl-2 pr-2">
                                            <i style="color: #fff;font-size: 18px;" class="fa fa-calendar"></i></span>
                                    </div>
                                    <!--
                                    <hr style="margin-top: 0px;margin-bottom: 0px;border-top: none">
                                    <div class="input-group">
                                        <input type="time" class="form-control input-w border-50 mb-3" id="event-time1"
                                            name="StartTime" required>
                                        <span style="padding-top: 14px;"
                                            class="input-group-addon fa-input pl-2 pr-2 mb-3">
                                            <i style="color: #fff;font-size: 20px;" class="fa fa-clock-o"></i></span>
                                    </div>
                                    -->

                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="event-date1" class="col-md-4 col-form-label text-md-left">
                                </label>
                                <div class="col-md-6">
                                    <div class="input-group date">
                                        <input type="text" class="form-control input-w input-date inp-group-radius-left"
                                            id="event-date2" name="EndDate" onchange="checkTimestamp()" onfocus="timestampOnFocus()" required>
                                        <input type="time" class="form-control input-w" id="event-time2" name="EndTime"
                                            value="12:00:00" onchange="checkTimestamp()" onfocus="timestampOnFocus()" required>
                                        <span class="input-group-addon fa-input pl-2 pr-2">
                                            <i style="color: #fff;font-size: 18px;" class="fa fa-calendar"></i></span>
                                    </div>
                                    <!--
                                    <hr style="margin-top: 0px;margin-bottom: 0px;border-top: none">
                                    <div class="input-group">
                                        
                                        <span style="padding-top: 14px;"
                                            class="input-group-addon fa-input pl-2 pr-2 mb-3">
                                            <i style="color: #fff;font-size: 20px;" class="fa fa-clock-o"></i></span>
                                    </div>
                                    -->
                                </div>

                            </div>
                            <div style="display: none;" class="form-group row timestamp-error">
                                <label class="col-md-4 col-form-label text-md-left">
                                </label>
                                <div id="timestamp-error" class="col-md-6"></div>

                            </div>

                            <hr class="w-100 mt-5 mb-5">

                            <div class="col-md-6 offset-md-4">
                                <button type="submit" id="submitEventForm"
                                    style="width: 200px;background: #07071c;height: 45px;font-size: 15px;"
                                    class="btn btn-primary text-left border-50">
                                    <strong>Next step</strong> <span style="margin-left: 97px;"><i
                                            class="fa fa-arrow-right" aria-hidden="true"></i></span>
                                </button>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

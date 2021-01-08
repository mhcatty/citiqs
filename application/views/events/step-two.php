<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-dark" id="editModalLabel">Edit Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <form name="my-form" action="<?php echo base_url(); ?>events/save_ticket_options" method="POST">
                <ul>
                    <li>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" id="guestTicketCheck" type="checkbox" checked="checked">
                            <label class="custom-control-label font-weight-bold text-dark" for="guestTicketCheck">
                                Can be used as guest ticket
                            </label>
                        </div>
                    </li>
                    <li>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" id="ticketSwapCheck" type="checkbox" checked="checked">
                            <label class="custom-control-label font-weight-bold text-dark" for="ticketSwapCheck">
                                Ticket can be swapped on Ticketswap
                            </label>
                        </div>
                    </li>
                    <li>
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" id="partialAccessCheck" type="checkbox"
                                checked="checked">
                            <label class="custom-control-label font-weight-bold text-dark" for="partialAccessCheck">
                                Partial access during event
                            </label>
                        </div>
                    </li>
                </ul>
                <hr class="w-100 mt-3 mb-3">
                <h3 class="font-weight-bold text-dark">Ticketfee per ticket</h3>
                <div class="row mb-2">
                    <div class="col-md-3 text-dark">Non shared ( Min 0.00 )</div>
                    <div class="col-md-3">
                        <input type="number" id="nonSharedTicketFee" name="nonSharedTicketFee" class="form-control inp-height" min="1"
                             value="1">
                    </div>

                    <div class="col-md-3">Shared ( Min 0.00 )</div>
                    <div class="col-md-3">
                        <input type="number" id="sharedTicketFee" name="sharedTicketFee" class="form-control inp-height" min="1" value="1">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 text-dark">Max Discount</div>
                    <div class="col-md-3">
                        <input type="number" id="maxDiscount" name="maxDiscount" class="form-control inp-height" min="1"
                            value="1">
                    </div>
                </div>
                <hr class="w-100 mt-3 mb-3">
                <h3 class="font-weight-bold text-dark">Ticket sales</h3>
                <div class="row">

                    <!--Grid column-->
                    <div class="col-md-6 mb-4">



                        <!-- Default unchecked -->
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="manually"
                               value="manually" name="ticketExpired" checked="">
                            <label class="custom-control-label text-dark" for="manually">On date and time</label>
                        </div>

                        <div class="my-2"></div>

                        <!-- Default checked -->
                        <div class="custom-control custom-radio">
                            <input type="radio" class="custom-control-input" id="automatically" value="automatically"
                                name="ticketExpired">
                            <label class="custom-control-label text-dark" for="automatically">
                                Automatically when ticket is almost sold out
                            </label>
                        </div>
                        </section>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col col-md-3">
                        From Date
                    </div>
                    <div class="col col-md-3">
                        <div class="input-group date">
                            <input type="text" class="form-control inp-height" id="startDate" name="startDate">
                            <span style="padding-top: 5px;" class="input-group-addon fa-input pl-2 pr-2">
                                <i style="color: #fff;font-size: 18px;" class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col col-md-3">
                        From Time
                    </div>
                    <div class="col col-md-3">
                        <div class="input-group">
                            <input type="time" class="form-control inp-height" id="startTime" name="startTime">
                            <span style="padding-top: 5px;" class="input-group-addon fa-input pl-2 pr-2">
                                <i style="color: #fff;font-size: 20px;" class="fa fa-clock-o"></i></span>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col col-md-3">
                        To Date
                    </div>
                    <div class="col col-md-3">
                        <div class="input-group date">
                            <input type="text" class="form-control inp-height" id="endDate" name="endDate">
                            <span style="padding-top: 5px;" class="input-group-addon fa-input pl-2 pr-2">
                                <i style="color: #fff;font-size: 18px;" class="fa fa-calendar"></i></span>
                        </div>
                    </div>
                    <div class="col col-md-3">
                        To Time
                    </div>
                    <div class="col col-md-3">
                        <div class="input-group">
                            <input type="time" class="form-control inp-height" id="endTime" name="endTime">
                            <span style="padding-top: 5px;" class="input-group-addon fa-input pl-2 pr-2">
                                <i style="color: #fff;font-size: 20px;" class="fa fa-clock-o"></i></span>
                        </div>
                    </div>


                </div>

                <div class="row">
                    <div class="col col-md-3">
                        Sold out when expired
                    </div>
                    <div class="col col-md-3">
                        <ul>
                            <li>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" id="soldout" type="checkbox">
                                    <label class="custom-control-label font-weight-bold text-dark" for="sold-out">

                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>


                </div>

                <hr class="w-100 mt-3 mb-3">
                <h3 class="font-weight-bold text-dark">Mail per amount of ticket sold</h3>
                <div class="row mb-3">
                    <div class="col col-md-3">Get email per</div>
                    <div class="col col-md-3">
                        <input type="number" id="mailPerAmount" name="mailPerAmount" class="form-control inp-height" min="1"
                            value="1">
                    </div>
                    <div class="col col-md-3">ticket sold</div>
                </div>
                <div class="row mb-3">
                    <div class="col col-md-3">On email address</div>
                    <div class="col col-md-6">
                        <input type="text" id="emailAddress" name="emailAddress" class="form-control inp-height">
                    </div>
                </div>

                <input type="hidden" id="guestTicket" name="guestTicket" value="1">
                <input type="hidden" id="ticketSwap" name="ticketSwap" value="1">
                <input type="hidden" id="partialAccess" name="partialAccess" value="1">
                <input type="hidden" id="soldoutExpired" name="soldoutExpired" value="0">
                <input type="hidden" id="ticketId" name="ticketId">



            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>



<!-- Add Ticket Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-bold text-dark" id="editModalLabel">Add Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card-body">
                    <form name="my-form" action="<?php echo base_url(); ?>events/save_ticket" method="POST">
                        <div class="form-group row">
                            <label for="ticket-name" class="col-md-4 col-form-label text-md-left">Ticket Name</label>
                            <div class="col-md-6">

                                <input type="text" id="ticketDescription" class="input-w form-control"
                                    name="ticketDescription">

                            </div>
                        </div>

                        <!--
                        <div class="form-group row">
                            <label for="ticketEvent" class="col-md-4 col-form-label text-md-left">Ticket event</label>
                            <div class="col-md-6">
                                <select id="ticketEvent" class="form-control input-w">
                                    <option selected disabled>Select option</option>
                                    <?php foreach($events as $event): ?>
                                    <option value="<?php echo $event['id']; ?>"><?php echo $event['eventname']; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                
                            </div>
                        </div>
                        -->
                        <input type="hidden" id="eventId" name="eventId" value="<?php echo $eventId ;?>">

                        <div class="form-group row">
                            <label for="ticketType" class="col-md-4 col-form-label text-md-left">Ticket type</label>
                            <div class="col-md-6">
                                <select id="ticketType" class="form-control input-w">
                                    <option selected disabled>Select option</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                </select>
                                <input type="hidden" id="ticketTypeVal" name="ticketType">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="quantity" class="col-md-4 col-form-label text-md-left">Ticket quantity</label>
                            <div class="col-md-6">
                                <input type="number" id="quantity" class="form-control input-w" name="ticketQuantity">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="price" class="col-md-4 col-form-label text-md-left">Ticket price</label>
                            <div class="col-md-6">
                                <input type="number" id="price" class="form-control input-w" name="ticketPrice">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-4 col-form-label text-md-left">
                                Ticket Visible
                            </div>
                            <div class="col-md-4">
                                <ul>
                                    <li>
                                        <div class="custom-control custom-checkbox">
                                            <input class="custom-control-input" id="visible" type="checkbox" checked>
                                            <label class="custom-control-label font-weight-bold text-dark"
                                                for="visible">

                                            </label>
                                            <input type="hidden" id="ticketVisible" name="ticketVisible" value="1">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="group" class="col-md-4 col-form-label text-md-left">Ticket group</label>
                            <div class="col-md-6">
                                <select id="group" class="form-control input-w">
                                    <option selected disabled>Select option</option>
                                    <option value="1">Group</option>
                                    <option value="2">VIP</option>
                                </select>
                                <input type="hidden" id="ticketGroup" name="ticketGroupId">
                            </div>
                        </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>

<main class="my-form">
    <div class="w-100 mt-5 p-3">
        <div class="input-group col-md-2">
            <input type="button" value="Add Ticket" style="background: #009933 !important;border-radius:0"
                class="btn btn-success form-control mb-3 text-left" id="event-time2" name="event-time2"
                data-toggle="modal" data-target="#addModal">
            <a style="background: #004d1a;padding-top: 14px;" class="input-group-addon pl-2 pr-2 mb-3"
                data-toggle="modal" data-target="#addModal">
                <i style="color: #fff;font-size: 18px;" class="fa fa-plus"></i></a>
        </div>

        <p class="mt-2"><strong style="font-size: 16px;" class="ml-1">Tickets</strong></p>
        <table id="tickets" class="mt-2" style="width:100%">

        </table>
    </div>
</main>
<hr class="w-100 mt-5 mb-5">
<!--
<strong style="font-size: 16px;" class="ml-1">Add tickets</strong>
<div class="row mt-2">
    <div class="col-md-4 ml-1">
        <div class="input-group">
            <input type="submit" value="Ticket" style="background: #377E7F !important;border-radius:0"
                class="btn btn-primary form-control mb-3 text-left" id="event-time2" name="event-time2">
            <span style="background: #275C5D;padding-top: 14px;" class="input-group-addon pl-2 pr-2 mb-3">
                <i style="color: #fff;font-size: 18px;" class="fa fa-plus"></i></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 ml-1">
        <div class="input-group">
            <input type="submit" value="Guest ticket" style="background: #C84746 !important;border-radius:0"
                class="btn btn-primary form-control mb-3 text-left" id="event-time2" name="event-time2">
            <span style="background: #B23938;padding-top: 14px;" class="input-group-addon pl-2 pr-2 mb-3">
                <i style="color: #fff;font-size: 18px;" class="fa fa-plus"></i></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 ml-1">
        <div class="input-group">
            <input type="submit" value="Additional item" style="background: #E3A847 !important;border-radius:0"
                class="btn btn-primary form-control mb-3 text-left" id="event-time2" name="event-time2">
            <span style="background: #F1993F;padding-top: 14px;" class="input-group-addon pl-2 pr-2 mb-3">
                <i style="color: #fff;font-size: 18px;" class="fa fa-plus"></i></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 ml-1">
        <div class="input-group">
            <input type="submit" value="Divider" style="background: #39495C !important;border-radius:0"
                class="btn btn-primary form-control mb-3 text-left" id="event-time2" name="event-time2">
            <span style="background: #2D3A4C;padding-top: 14px;" class="input-group-addon pl-2 pr-2 mb-3">
                <i style="color: #fff;font-size: 18px;" class="fa fa-plus"></i></span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 ml-1">
        <div class="input-group">
            <input type="submit" value="External ticket link" style="background: #39495C !important;border-radius:0"
                class="btn btn-primary form-control mb-3 text-left" id="event-time2" name="event-time2">
            <span style="background: #2D3A4C;padding-top: 14px;" class="input-group-addon pl-2 pr-2 mb-3">
                <i style="color: #fff;font-size: 18px;" class="fa fa-plus"></i></span>
        </div>
    </div>
</div>
<hr class="w-100 mt-5 mb-5">
-->
<div class="row">
    <div class="col-md-3 ml-1">
        <div class="input-group">
            <span style="background: #030F16;padding-top: 14px;" class="input-group-addon pl-2 pr-2 mb-3">
                <i style="color: #fff;font-size: 18px;" class="fa fa-arrow-left"></i></span>
            <input type="submit" value="Next Step" style="background: #111B22 !important;border-radius:0"
                class="btn btn-primary form-control mb-3 text-left" id="event-time2" name="event-time2">

        </div>
    </div>
    <div class="col-md-3">
        <div class="input-group">
            <input type="submit" value="Save changes" style="background: #377E7F !important;border-radius:0"
                class="btn btn-primary form-control mb-3 text-left" id="event-time2" name="event-time2">
            <span style="background: #275C5D;padding-top: 14px;" class="input-group-addon pl-2 pr-2 mb-3">
                <i style="color: #fff;font-size: 18px;" class="fa fa-check"></i></span>
        </div>
    </div>
    <div class="col-md-3 ml-1">
        <div class="input-group">
            <input type="submit" value="Next Step" style="background: #111B22 !important;border-radius:0"
                class="btn btn-primary form-control mb-3 text-left" id="event-time2" name="event-time2">
            <span style="background: #030F16;padding-top: 14px;" class="input-group-addon pl-2 pr-2 mb-3">
                <i style="color: #fff;font-size: 18px;" class="fa fa-arrow-right"></i></span>
        </div>
    </div>
</div>
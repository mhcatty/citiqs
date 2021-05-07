<!-- HERO SECTION -->
<?php if($this->session->flashdata('expired')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong><?php echo ucfirst($this->session->flashdata('expired')); ?></strong>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php endif; ?>
<input type="hidden" id="shop" value="shop">
<section id="main-content" class='hero-section position-relative'>
    <div class="d-none d-md-flex col-6 px-0 hero__background">
    <?php if(isset($agendas[0]) && $agendas[0]->backgroundImage != ''): ?>
        <img id="background-image" src="<?php echo base_url(); ?>assets/home/images/<?php echo $agendas[0]->backgroundImage ; ?>"
            alt="">
    <?php else: ?>
        <img id="background-image" src="<?php echo base_url(); ?>assets/images/events/default_background.webp"
            alt="">
    <?php endif; ?>

    </div>

    <!-- end col -->
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-6">
                <h1 id="event-title" class="event-title">Our Events</h1>
                <p id="event_text_descript" class="text-muted mt-4 mb-5">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin sagittis
                    at est ut facilisis. Suspendisse eu luctus mauris.</p>

            </div>
            <!-- end col -->

        </div>
        <input type="hidden" id="exp_time" name="exp_time" value="1">
        <!-- end row -->
       
        <?php if (!empty($agendas)): ?> 

        <div id="events" style="box-shadow: 0 0 70px 30px #00000014 !important;background: #00000014;padding: 0px 0px;"
            class="row single-item__grid">
            <?php foreach ($agendas as $key => $agenda):
            $date = $agenda->ReservationDateTime;
            $unixTimestamp = strtotime($date);
            $dayOfWeek = date("N", $unixTimestamp);
            $dayOfWeekWords = date("l", $unixTimestamp);
                  if($key == array_key_first($agendas)):
            ?>
            <input type="hidden" id="first_element" value="<?php echo $agenda->id; ?>" data-date="<?php echo date('Ymd', strtotime($agenda->ReservationDateTime)); ?>">
            <?php endif; ?>
            <input type="hidden" id="background_img_<?php echo $agenda->id; ?>" value="<?php echo $agenda->backgroundImage; ?>">
            <div id="event_<?php echo $agenda->id; ?>"
                class="col-12 col-sm-6 col-md-3 single-item mb-4 mb-md-0 bg-white p-4">
                <a href="#spots" onclick="getSpotsView('<?php echo $agenda->id; ?>', <?php echo date('Ymd', strtotime($agenda->ReservationDateTime)); ?>)"
                    class="single-item btn-ticket">
                    <div class="single-item__image">
                        <img 
                        <?php if($dayOfWeek): ?>
                        style="object-fit: ;min-height: auto;"
                        src="<?php echo base_url(); ?>assets/home/images/logo1.png"
                        <?php else: ?>
                        src="<?php echo $this->baseUrl; ?>assets/home/images/<?php echo $dayOfWeek; ?>.png"
                        <?php endif; ?>
                        alt="<?php echo $agenda->ReservationDescription; ?>">
                        <p class='single-item__promotion'>Book Now</p>
                    </div>
                    <div class="single-item__content">
                        <p class='mb-0'><?php echo $agenda->ReservationDescription; ?></p>
                        <div>
                            <span class='single-item__price'><?php echo date("d.m.Y", strtotime($agenda->ReservationDateTime)); ?></span>
                        </div>
                    </div>
                </a>
            </div>

            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        <!-- end row -->
    </div>
</section>
<!-- END HERO SECTION -->

<script>
(function(){
    changeTextContent();
}());
</script>
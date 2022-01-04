<!DOCTYPE html>
<html>
    <head>
        <?php $this->load->view('head'); ?>
    </head>
    <body>
        <div class="site_container">
            <?php $this->load->view('menu'); ?>
            <?php
            if (isset($content) && $content) {
                $this->load->view($content);
            }
            ?>
            <?php $this->load->view('footer'); ?>
        </div>
        <div class="background_overlay"></div>
        <!--js-->
        <?php $this->load->view('js'); ?>
    </body>
</html>
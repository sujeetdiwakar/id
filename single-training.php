<?php
session_start();
global $current_user;
$user_id = get_current_user_id();
global $post;

$post_id = $post->ID;
$post_slug = $post->post_name;
if ( is_user_logged_in() ){
if(isset($_POST[$post_slug])){
    if(!($_SESSION[$post_slug])){
        $_SESSION[$post_slug]= 1;
    }else{
        $count = $_SESSION[$post_slug] + 1;
        $_SESSION[$post_slug] = $count;
    }
}
}
/*
print_r($_POST);
print_r($_SESSION);
*/
$array = array();
if ($categories = get_the_terms(get_the_ID(), 'training_cat')) {
    $current_cat = $categories[0]->term_id;
}

$free_cat = get_field('select_sector', 'option');
$free_posts = get_field('select_courses', 'option');
if ($user_id) {
    $user = wp_get_current_user();
    if (in_array('administrator', (array)$user->roles)) {

    } else {
        global $wpdb;
        $date = date('Y-m-d');
        $table = $wpdb->prefix . 'ihc_user_levels';
        $q = $wpdb->prepare("SELECT custom_cat, expire_time FROM $table WHERE user_id=%d AND date(`expire_time`) >= '$date'", $user_id);
        $data = $wpdb->get_results($q);
        if (!empty($data)) {
            foreach ($data as $object) {
                $array = explode(",", $object->custom_cat);
                //print_r($array);
                $arrays[] = $array;
                $expire_time = $object->expire_time;
            }

            if (!empty($arrays)) {
                //$array_cats = [];
                $array_cats = array_flatten($arrays);
                //$courses = [];
                foreach ($array_cats as $cat) {
                    $args = [
                        'post_type' => 'training',
                        'tax_query' => [
                            [
                                'taxonomy' => 'training_cat',
                                'field' => 'id',
                                'terms' => $cat

                            ]
                        ]
                    ];
                    $my_query = new WP_Query($args);
                    while ($my_query->have_posts()) {
                        $my_query->the_post();
                        $courses[] = get_the_ID();
                    }
                }


                $plan_expire = 1;
                //print_r(array_merge($free_posts, $courses));
                //echo "id".$post_id;
                if ((!in_array($post_id, array_merge($free_posts, $courses)))) {
                    wp_redirect(home_url());
                }

            }
            $plan_expire = 1;
        } else {
            if (!in_array(get_the_ID(), $free_posts)) {
                wp_redirect(home_url());
            }
        }

    }
} else {
    if (!in_array(get_the_ID(), $free_posts)) {
        wp_redirect(home_url());
    }

}


get_header();

get_template_part('template-parts/content', 'banner');

if (have_posts()) {
    // Start the loop.
    while (have_posts()) {
        the_post(); ?>

        <section class="section_course">
             <div class="container">
                    <div class="row">
                        <div class="col-lg-9">
                            <!-- main-blog-wrap -->
                            <div class="main-blog-wrap">
								<?php if(has_post_thumbnail()): ?>
                                <img src="<?php the_post_thumbnail_url(); ?>">
								<?php endif; ?>
                                <div class="main-blog-wrap_content">
                                    <div class="title-wraper">
                                        <div class="lesson-title">
                                            <?php if($lession = get_field('lession_no')): ?>
                                                <span class="lesson-number"><?php echo $lession; ?>:</span>
                                            <?php endif; ?>
                                            <h2><?php the_title(); ?></h2>
                                        </div>
                                        <div class="title-bg-style">
                                            <div class="border-style-wraper">
                                                <span class="red-bg-border"></span>
                                                <span class="blue-bg-border"></span>
                                            </div>
                                        </div>
                                    </div>
                                      
                                      <?php the_content(); ?>
                               
                                    <?php 
                                    while(have_rows('course_contents')): the_row();

                                        if(get_row_layout() == 'content_image_section'):

                                            get_template_part('template-course/content','image');

                                        elseif(get_row_layout() == 'content_list_section'):

                                            get_template_part('template-course/content','list');

                                        elseif(get_row_layout() == 'case_study_section'):
                                        
                                             get_template_part('template-course/content','case');

                                        elseif(get_row_layout() == 'content_section'):

                                            get_template_part('template-course/content','text');
        
                                        elseif(get_row_layout() == 'brief_section' ):
        
                                            get_template_part('template-course/content','brief');

                                        endif;    

                                    endwhile;
                                    //echo @$_SESSION[$post_slug];
                                    ?>    
                                 <div id="quiz-start"></div>
                                 <?php if (have_rows('quiz')): ?>
                                    <div id="<?php echo(@$_REQUEST['disp'])?'js-again':''; ?>" class="button_start text-center mt-4"
                                         style="display:<?php echo ($_POST['check']) ? 'none' : 'block'; ?>">
                                        <a href="#" class="js-start btn btn-primary btn btn-primary">Start Quiz</a>
                                    </div>
                                <?php endif; ?>
                            

                                   







                                    <div class="quiz"
                                         style="display:<?php echo ($_POST['check']) ? 'none' : 'none'; ?>">
                                        <?php
                                        if ($quiz_heading = get_field('quiz_heading')): ?>
                                            <h2><?php echo $quiz_heading; ?></h2>
                                        <?php endif;
                                        echo $_SESSION[$post_slug]['count'];
                                        if (have_rows('quiz')): ?>
                                        <form action="<?php the_permalink(); ?>" method="post">
                                            <?php
                                            $i = 1;
                                            while (have_rows('quiz')) {
                                                the_row();
                                                if ($q = get_sub_field('question')):
                                                    $true_option = get_sub_field('true_option'); ?>
                                                    <div class="card-header">
                                                        <h5><?php echo $i; ?>) <?php echo $q; ?></h5>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="card-body">
                                                    <?php
                                                    $j = 1;
                                                    while (have_rows('options')) {
                                                        the_row();

                                                        if ($option = get_sub_field('option')):?>

                                                            <lable><input type="radio" name='check[<?php echo $i; ?>]'
                                                                   value="<?php echo $option; ?>" <?php if (@$_POST['check']) {
                                                                echo (in_array($option, @$_POST['check'])) ? 'checked' : '';
                                                            } ?> required></lable> <span><?php echo $option; ?></span><br>

                                                        <?php endif;
                                                        $j++;
                                                    } ?>
                                                </div>
                                                <?php $i++;
                                            }

                                            $total = $i - 1; ?>
                                            <div class="card-footer text-center">
                                                <input type="submit" name="sbu" class="btn btn-primary" value="Submit"><br><br>
                                                <input type="hidden" name="caller" value="self">
                                                <input type="hidden" name="<?php echo $post_slug; ?>" value="cn">
                                            </div>

                                        </form>
                                    </div>
                                    <div class="card-body" id="<?php echo ($_POST['check']) ? 'js-check' : ''; ?>"
                                         style="display: <?php echo ($_POST['check']) ? 'block' : 'none'; ?>">

                                        <h2>Result</h2>
                                        <?php
                                        $c = 0;
                                        $i = 1;
                                        //print_r($_POST['check'][1]);
                                        while (have_rows('quiz')) {
                                            the_row();
                                            if ($q = get_sub_field('question')):
                                                $true_option = get_sub_field('true_option'); ?>
                                                <div class="card-header">
                                                    <h5><?php echo $i; ?>) <?php echo $q; ?></h5>
                                                </div>
                                            <?php endif; ?>
                                            <div class="card-body">
                                                <?php

                                                while (have_rows('options')) {
                                                    the_row();
                                                    

                                                    if ($option = get_sub_field('option')):
                                                    
                                                        if((stripslashes(@$_POST['check'][$i]) == $true_option) && ($option == $true_option)){
                                                            $c++;              
                                                        }
                                                    
                                                    ?>

                                                        <label><input type="radio"
                                                               class="<?php if (in_array($option, @$_POST['check'])) {
                                                                   //echo ($option === $true_option ) ? 'correct' : 'error';
                                                                    echo ($option == $true_option && $true_option == @$_POST['check'][$i] ) ? 'correct' : '';
                                                               } ?>"
                                                               value="<?php echo $option; ?>" <?php if (@$_POST['check']) {
                                                            //echo (in_array($option, @$_POST['check'][$i])) ? 'checked' : '';

                                                            echo ($option == stripslashes(@$_POST['check'][$i])) ? 'checked' : '';
                                                            

                                                        } ?> disabled></label> <span><?php echo $option; if($_SESSION[$post_slug] > 3): echo ($option === $true_option ) ? ' ' : ' <i class="fa fa-close"></i>'; else:?><i class="fa fa-close"></i><?php endif; ?> </span><br>

                                                    <?php endif;
                                                    /*
                                                    echo $_POST['check'][$i];
                                                            echo "<br>";
                                                            echo "option".$option;
                                                            echo "<br>";
                                                            echo "true option".$true_option;
                                                         */   

                                                }

                                                if($_SESSION[$post_slug] > 3):?>
                                                <p class="text-success">
                                                    <strong>Correct:</strong><?php echo $true_option; ?></p>
                                                <?php endif; ?>
                                            </div>
                                            <?php
                                             $i++;
                                        }

                                        ?>








                                        <?php
                                        $res = 0;
                                        $i = 1;
                                        //echo "<pre>";
                                        //print_r($_POST);
                                        //echo "</pre>";
                                        if (isset($_POST['caller'])) {

                                            if (!empty($_POST['check'])) {
                                                $match = count($_POST['check']);
                                                $select = $_POST['check'];

                                                while (have_rows('quiz')) {
                                                    the_row();
                                                    $aid = get_sub_field('true_option');
                                                    $check = $aid == $select[$i];
                                                    if ($check) {

                                                        $res++;
                                                    }
                                                    $i++;
                                                }

                                            }
                                            if (empty($_POST['check'])) {
                                                $match = count($_POST['check']);
                                                echo "<p>Please select the options </p>";
                                            }
                                            
                                            $percentage = ($c / $total) * 100;

                                            ?>
                                            <h2>Thanks For Submitting</h2>
                                            <h4>Scored <?php echo $percentage . '%'; ?></h4>
                                            <?php
                                        }
                                        ?>

                                        <?php endif; 
                                        if($percentage >= 70){

                                            $font1= get_stylesheet_directory() ."/BRUSHSCI.TTF";
                                            $font2= get_stylesheet_directory() ."/AGENCYR.TTF";
                                            $certificate = get_template_directory_uri().'/certificate.jpg';
                                            $image=imagecreatefromjpeg($certificate);
                                            $color=imagecolorallocate($image,19,21,22);

                                            if ( is_user_logged_in() ) {
                                                $name = ucwords($current_user->display_name);
                                            }else{
                                                $name="Your Name";
                                            }
                                            //echo $name;
                                            imagettftext($image,50,0,365,420,$color,$font1,$name);
                                            $date= date('d M Y');
                                            imagettftext($image,20,0,450,595,$color,$font2,$date);
                                            $file=time();
                                            imagejpeg($image, get_stylesheet_directory() ."/certificate/".$file.".jpg");
                                            require(get_stylesheet_directory() .'/fpdf.php');
                                            $pdf = new FPDF('L','in',[11.7,8.27]);
                                            $pdf->AddPage();
                                            $pdf->Image(get_stylesheet_directory() ."/certificate/".$file.".jpg",0,0,11.7,8.27);
                                            $pdf->Output(get_stylesheet_directory() ."/certificate/".$file.".pdf","F");

                                            imagedestroy($image);
                                            //$download = get_template_directory_uri()."/certificate/".$file.".jpg";
                                            $download = get_template_directory_uri()."/certificate/".$file.".pdf";
                                            //echo "<img src='$download'>";
                                            echo "<p class='result-success'>Congratulations! Keep up the good work. <i class='fa fa-thumbs-up'></i> </p>";
                                            echo "<a href='$download' download>Download Certificate</a>";

                                        }
                                        if($percentage <= 70 ):?>
                                        <div class="start-again">
                                            <p class='result-fail'>Oops... looks like you couldn't achieve the minimum required score  Please don't feel discouraged, you have another chance. <!-- <img src="https://pnjmarketing.online/ildedu/wp-content/themes/ildedu/images/sad.gif"> -->
                                            <a href="<?php the_permalink(); ?>?disp=s" class="start_again_button">Start Again</a>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="col-lg-3">
                            <?php while (have_rows('advertisements', 'training_cat_' . $categories[0]->term_id)): the_row();
                                        $title = get_sub_field('advertisement_title');
                                        $content = get_sub_field('advertisement_content');
                                        $image = get_sub_field('advertisement_image');
                                        if (!empty($title) || !empty($content) || !empty($image)):?>
                                    <div class="right_side_content">
                                        <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                                        <?php if($title): ?>
                                            <h2><?php echo $title; ?></h2>
                                        <?php endif; ?>    
                                        <div class="subcontent_right">
                                            <p><?php echo $content; ?></p>
                                        </div>
                                    </div>
                            <?php endif;
                             endwhile; ?>
                        </div>
                    </div>
                </div>
        </section>

        <?php
    }
} else {

    get_template_part('template-parts/content', 'none');
}

get_footer();

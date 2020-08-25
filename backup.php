<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */
/**
 * Load child theme css and optional scripts
 *
 * @return void
 */

add_action('init', 'myStartSession', 1);
add_action('wp_logout', 'myEndSession');
add_action('wp_login', 'myEndSession');

function myStartSession() {
    if(!session_id()) {
        session_start();
    }
}

function myEndSession() {
    session_destroy ();
}


add_theme_support( 'post-thumbnails' );

function hello_elementor_child_enqueue_scripts() {
    wp_enqueue_style('hello-elementor-child-style', get_stylesheet_directory_uri() . '/style.css',
        [
            'hello-elementor-theme-style',
        ],
        '1.0.0'
    );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts' );

function mytheme_add_woocommerce_support() {
add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

//Toms functions


// Removes stuff from the website

    // Remove price 

add_filter( 'woocommerce_get_price_html', function( $price ) {
    if ( is_admin() ) return $price;

    return '';
} );

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart');
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title');


    // Remove Backend Woocommerce boxes 

add_action( 'add_meta_boxes_product', 'bbloomer_remove_metaboxes_edit_product', 9999 );
 
function bbloomer_remove_metaboxes_edit_product() {
 
        // e.g. remove short description
        remove_meta_box( 'postexcerpt', 'product', 'normal' );
 
        // e.g. remove product tags
        remove_meta_box( 'tagsdiv-product_tag', 'product', 'side' );
 
}

function remove_product_editor() {
  remove_post_type_support( 'product', 'editor' );
}
add_action( 'init', 'remove_product_editor' );


// Start of the custom Archive

    //Adds the key features logo

add_action('woocommerce_before_shop_loop_item_title','tjames_adds_key_feats', 20);

function tjames_adds_key_feats() {
    global $product;
    echo '<div class="key_feats_and_badge">';
                if( have_rows('main_details') ):
        // loop through the rows of data
        while ( have_rows('main_details') ) : the_row();
    if( $sale_badge = get_sub_field( 'sale_badge', $product->get_id() ) ) {
        $sale_badge = str_replace(' ', '_', $sale_badge);
        echo '<img src="/wp-content/uploads/'. $sale_badge . '.png" class="new_sale_badge" />';
    }
        endwhile;

    else :

    // no rows found

    endif;
        if( have_rows('key_features') ):
        // loop through the rows of data
        while ( have_rows('key_features') ) : the_row();
    
    if( $dab = get_sub_field( 'dab_radio', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/dab-radio.png" class="dab_icon" />';
    }
   if( $bluetooth2 = get_sub_field( 'bluetooth', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/bluetooth.png" class="dab_icon" />';
    }
    if( $cruisecontrol2 = get_sub_field( 'cruise_control', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/cruise-control.png" class="dab_icon" />';
    }
    if( $aircon2 = get_sub_field( 'air_conditioning', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/air-con.png" class="dab_icon" />';
    }
    if( $satnav2 = get_sub_field( 'satellite_navigation', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/sat-nav.png" class="dab_icon" />';
    }
    if( $heatedseats2 = get_sub_field( 'heated_seats', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/heated-seats.png" class="dab_icon" />';
    }
    if( $applecc = get_sub_field( 'apple_carplay', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/apple-carplay-1.png" class="dab_icon" />';
    }


    endwhile;

    else :

    // no rows found

    endif;

    echo '</div>';
}

// Adds the USP and the our price message

add_action('woocommerce_shop_loop_item_title','tjames_adds_the_usp',20);

function tjames_adds_the_usp() {
    
    global $product;

    $url2 = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

    echo '<div class="archive_prive_container">';
    echo '<h2 class="post-title">'.$product->post->post_title . '</h2>';
    echo '</div>';
    echo '<div class="usp">';
    if( have_rows('main_details') ):
    // loop through the rows of data
    while ( have_rows('main_details') ) : the_row();
        echo '<div class="usp_messages">';
    if ( $mainusps  = get_sub_field('usp_archive', $product->get_id()) ) {
        echo '<p class="main_usp">' . $mainusps . '';
    }
/*    if ( $secondusp = get_sub_field('second_usp', $product->get_id()) ) {
    echo '<p class="second_usp">' . $secondusp . '</p>';
    }
*/
    endwhile;

    else :

    // no rows found

    endif;
   echo '</div>';

    echo '</p>';
    /*if ( $subusps  = get_field('sub_usp', $product->get_id()) ) {
        echo '<p class="sub_usp">' . $subusps . '</p></p>';
    }*/
    echo '</div>';


}

add_action('woocommerce_shop_loop_item_title','add_archive_price',20);




function add_artrchive_price() {

    global $product;

    $url2 = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

    echo '<p class="ourprice">Our price</p>';
    echo '<div class="archive_prive_container">';
    echo '<p class="archive_price">£';
            // if url has personal in it
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                //unless personal price is added
                if ( $personalprice  = get_field('main_personal_price_alt', $product->get_id()) ) {
                    //display personal price
                the_field('main_personal_price_alt');
                echo '</p>';
                echo '<p class="vat">';
                echo 'inc VAT';
                echo '</p>';
                } else {
                    //display business price with 20 percent tax
                $currentprice = get_field('main_business_price');
                $tax = $currentprice / 100 * 20;
                $changeprice = $currentprice + $tax;
                print number_format((float)$changeprice, 2, '.', '');
                echo '<p class="vat">';
                echo 'inc VAT';
                echo '</p>';

            } } else {
                //display the original business price when url doesn't contain personal
                if ( $mainbusinessprice  = get_field('main_business_price', $product->get_id()) ) {
                    //display personal price
                    print number_format((float)$mainbusinessprice, 2, '.', '');
                }
                if ( $mainbusinessprice == 0){
                    echo 'call';
                }
                echo '<p class="vat">';
                echo 'exc VAT';
                echo '</p>';
            }
            echo ' </p>';
            echo '</div>';


    $link = $product->get_permalink();
    echo '<div class="quotes"> <a href="' . $link . '" class="button addtocartbutton">Get a Quote</a>';
    echo '<a href="' . $link . '" class="button addtocartbutton">View Deal</a></div>';
}

// Archive ends

//single Product page Starts Here

//adds the product title to the SPP

add_action('woocommerce_after_single_product','new_product_title', 20);

function new_product_title() {
        global $product;

    if( $make = get_field( 'make', $product->get_id() ) ) {
        echo '<div class="SP_container"><p class="product-title"><p class="make">' . $make . '';
    }
    if( $model = get_field( 'model', $product->get_id() ) ) {
        echo ' '. $model . '</br></p></p>';
    }
    if( $variant = get_field( 'variant', $product->get_id() ) ) {
        echo '<p class="car_variant">'. $variant . '</p>';
   }
       if( have_rows('main_details') ):
        // loop through the rows of data
    while ( have_rows('main_details') ) : the_row();
    echo '<div class="product_image"><div class="sale_icon">';
    if( $sale_badge = get_sub_field( 'sale_badge', $product->get_id() ) ) {
        $sale_badge = str_replace(' ', '_', $sale_badge);
        echo '<img src="/wp-content/uploads/'. $sale_badge . '.png" class="new_sale_badge" />';
    }
  //  echo '</div>';

        endwhile;

    else :

    // no rows found

    endif;

}


add_action( 'after_setup_theme', 'travel_eye_pro_setup' );
 
function travel_eye_pro_setup() {
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
}



    add_action('woocommerce_after_single_product','product_images', 20);

    function product_images() {

    global $product;

    echo $product->get_image();
    echo '</div>';

    }


    add_action('woocommerce_after_single_product','right_details', 20);


    function right_details() {

    global $product;

    echo '<div class="right_details"><div class="logo_n_price">';
    

    if( $manulogo = get_field( 'make', $product->get_id() ) ) {
        $manulogo = str_replace(' ', '-', $manulogo);
        echo '<a href="/make/' . $manulogo . '"><img src="/wp-content/uploads/' . strtoupper($manulogo). '-logo.png" class="manufacturer_logo" /></a>';;
    }



        $url2 = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];



if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
        echo '<div class="price_type"><p class="personal-lease">personal lease</p>';
    } else {
        echo '<div class="price_type"><p class="personal-lease">business lease</p>';
    }

        echo '<p class="from_price">from </p>';
        echo '<p class="price">£';
            // if url has personal in it
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                //unless personal price is added
                if ( $personalprice  = get_field('main_personal_price_alt', $product->get_id()) ) {
                    //display personal price
                the_field('main_personal_price_alt');
                echo '</p>';
                echo '<p class="vat">';
                echo 'inc VAT';
                echo '</p>';
                } else {
                    //display business price with 20 percent tax
                $currentprice = get_field('main_business_price');
                $tax = $currentprice / 100 * 20;
                $changeprice = $currentprice + $tax;
                print number_format((float)$changeprice, 2, '.', '');
                echo '<p class="vat">';
                echo 'inc VAT';
                echo '</p>';

            } } else {
                //display the original business price when url doesn't contain personal
                if ( $mainbusinessprice  = get_field('main_business_price', $product->get_id()) ) {
                    //display personal price
                    print number_format((float)$mainbusinessprice, 2, '.', '');
                }
                if ( $mainbusinessprice == 0){
                    echo 'call';
                }
                echo '<p class="vat">';
                echo 'exc VAT';
                echo '</p>';
            }
            echo ' </p>';
            echo '</div>';

        }

// adds the USP

    add_action('woocommerce_after_single_product','usps', 20);

    function usps() {

        global $product;

    if( have_rows('main_details') ):
        // loop through the rows of data
        while ( have_rows('main_details') ) : the_row();

            echo '<div class="main_usp">';

            if ( $mainusp  = get_sub_field('usp_product_page', $product->get_id()) ) {
                echo '<p class="main_usp">' . $mainusp . '</p>';
            }

/*            if ( $secondusp  = get_sub_field('second_usp', $product->get_id()) ) {
                echo '<p class="secondusp">' . $secondusp . '</p>';
            }
*/
            echo '</div>';
    endwhile;

    else :

    // no rows found

    endif;

      
}
    // no rows found

    add_action('woocommerce_after_single_product','kf_details', 20);

    function kf_details() {
        global $product;

    if( have_rows('key_features') ):
        // loop through the rows of data
    while ( have_rows('key_features') ) : the_row();

    echo '<p class="key_features">Key Features</p>';
    echo '<div class="key_feats_logos">';
    if( $dabtest = get_sub_field( 'dab_radio', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/kf_spp_dab.png" class="kf_icon" />';
    }
   if( $bluetooth2 = get_sub_field( 'bluetooth', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/kf_spp_bluetooth.png" class="kf_icon" />';
    }
    if( $cruisecontrol2 = get_sub_field( 'cruise_control', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/kf_spp_cruise.png" class="kf_icon" />';
    }
    if( $aircon2 = get_sub_field( 'air_conditioning', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/kf_spp_ac.png" class="kf_icon" />';
    }
    if( $satnav2 = get_sub_field( 'satellite_navigation', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/kf_spp_satnav.png" class="kf_icon" />';
    }
    if( $heatedseats2 = get_sub_field( 'heated_seats', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/kf_spp_heatedseats.png" class="kf_icon" />';
    }
    if( $applecc = get_sub_field( 'apple_carplay', $product->get_id() ) ) {
        echo '<img src="/wp-content/uploads/apple-carplay-logo.png" class="kf_icon" />';
    }
    echo '<div class="get_quote"><a href="#quote" class="get_quote_text">Get a Quote</a></div>';
    ;

    endwhile;

    else :

    // no rows found

    endif;

}


add_action('woocommerce_after_single_product','add_social_share_icons', 20);

function add_social_share_icons(){

    echo '<div class="ss_button_container">';
    echo '<div class="social_share"><p class="share_this_deal">share this deal!</p>';
    echo '<div class="share_icons">';

$url2 = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 


    if(get_option("social-share-facebook") == 1)
    {
        print '<a href="https://www.facebook.com/sharer/sharer.php?u='.$url3.'" target="_blank"><img src="/wp-content/uploads/facebook.png" class="social_icon" alt="Facebook Icon"></a>';
    }

    if(get_option("social-share-twitter") == 1)
    {
        print '<a href="https://twitter.com/intent/tweet?text='.$url3.'" target="_blank"><img src="/wp-content/uploads/twitter.png" class="social_icon" alt="Twitter Icon"></a>';
    }

    if(get_option("social-share-whatsapp") == 1)
    {
        print '<a href="whatsapp://send?text='.$url3.'" target="_blank"><img src="/wp-content/uploads/whatsapp.png" class="social_icon" alt="Whatsapp Icon"></a>';
    }
    
    if(get_option("social-share-pinterest") == 1)
    {
        print '<a href="https://pinterest.com/pin/create/button/?url='.$url3.'" target="_blank"><img src="/wp-content/uploads/pinterest.png" class="social_icon" alt="Pinterest Icon"></a>';
    }

    if(get_option("social-share-linkedin") == 1)
    {
        print '<a href="https://www.linkedin.com/shareArticle?mini=true&url='.$url3.'" target="_blank"><img src="/wp-content/uploads/linkedin.png" class="social_icon" alt="Linkedin Icon"></a>';
    }

    if(get_option("social-share-email") == 1)
    {
        print '<a href="mailto:enquires@contractcars.com" target="_blank"><img src="/wp-content/uploads/mail.png" class="social_icon" alt="Email Icon"></a>';
    }

  }

  echo '</div></div>';

  echo '</div></div></div></div></div>';

 echo '<p class="discalimer">Images are for illustration purposes only</p>';




  }





// Adds the Payment Profile to the Single Product page

add_action('woocommerce_after_single_product','newpaymentprofile', 20);


function newpaymentprofile() {

    global $product;

    $url2 = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                

                echo '<div class="payment_profile">';
                echo '<p class="Payment_Profile_Title">Payment Profiles</p>';
                
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                echo '<p class="business_rates">All personal leasing prices include VAT.</p>';

                
                } else {

                echo '<p class="business_rates">All business leasing prices exclude VAT.</p>';
                }
                // echo '<div class="payment-profile-toggle">';
                // echo do_shortcode( "[toggle]" );
                // echo '</div>';

                echo 


                '<table>
                    <thead>
                        <th>Contract Legnth</th>
                        <th>Profile</th>
                        <th>Mileage</th>
                        <th class="price">Monthly Price</th>
                    </thead>
                    <tbody>';


            //start of 18 month payment profile//
            if( have_rows('18_month_profile') ):
            // loop through the rows of data
            while ( have_rows('18_month_profile') ) : the_row();
            // display a sub field value

            if( $businessturnon = get_sub_field( 'business_18', $product->get_id() ) ) {
                     $businessprice189 = $businessturnon;
                    }

            if( $turnon246 = get_sub_field( '6_month_replacement_18', $product->get_id() ) ) {
                     $rep186 = $turnon246;
                    }

            if( $turnon243 = get_sub_field( '3_month_replacement_18', $product->get_id() ) ) {
                     $rep183 = $turnon243;
                    }

            if( $turnon241 = get_sub_field( '1_month_replacement_18', $product->get_id() ) ) {
                     $rep181 = $turnon241;
                    }

            if( $turnonprofile = get_sub_field( 'fee_18', $product->get_id() ) ) {
                     $profile18 = $turnonprofile;
                    }

                $personalprice189                   = ($businessprice189 / 100) * 20 + $businessprice189;
                $businessprice186                   = ($businessprice189 * 26 / 23) + 3;
                $personalprice186                   = ($businessprice186 / 100) * 20 + $businessprice186;
                $rep186plusvat                      = ($rep186 / 100) * 20 + $rep186;
                $businessprice183                   = ($businessprice189 * 26 / 20) + 5;
                $personalprice183                   = ($businessprice183 / 100) * 20 + $businessprice183;
                $rep183plusvat                      = ($rep183 / 100) * 20 + $rep183;
                $businessprice181                   = ($businessprice189 * 26 / 17) + 7.5;              
                $personalprice181                   = ($businessprice181 / 100) * 20 + $businessprice181;
                $rep181plusvat                      = ($rep181 / 100) * 20 + $rep181;
                $personalprofile18                  = ($profile18 / 100) * 20 + $profile18;



                if( $switchon = get_sub_field( 'active_18', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth18 = get_sub_field( 'contract_length_18', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth18 . '</td>';
                    }
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                
                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile18 . ' + 9 + 17</td>';
                    }
                    } else {
                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                    echo '<td>£' . $profile18 . ' + 9 + 17</td>';
                    } }
                    if( $mileage18 = get_sub_field( 'mileage_18', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage18) . ' Per Annum</td>';
                    }
            
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 

                    if( $personal249 = get_sub_field( 'personal_18', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$personal189, 2, '.', '').'<span class="lowprice">Lowest Price</span></td>';
                    } else {
                        print '<td class="price">£'.number_format((float)$personalprice189, 2, '.', '').'<span class="lowprice">Lowest Price</span></td>';
                    } } else {
                    if( $business249 = get_sub_field( 'business_18', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice189, 2, '.', '').'<span class="lowprice">Lowest Price</span></td>';
                    } 

                echo '</tr>';

                }


                }


                if( $switchon618 = get_sub_field( '6_month_18', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth18 = get_sub_field( 'contract_length_18', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth18 . '</td>';
                    }
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                
                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile18 . ' + 6 + 17</td>';
                    }
                    } else {
                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                    echo '<td>£' . $profile18 . ' + 6 + 17</td>';
                    } }
                    if( $mileage18 = get_sub_field( 'mileage_18', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage18) . ' Per Annum</td>';
                    }
            
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 

                        if( $rep6active18 = get_sub_field( '6_month_replacement_18', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$rep186plusvat, 2, '.', '').'</td>';
                        } else {
                        print '<td class="price">£'.number_format((float)$personalprice186, 2, '.', '').'</td>';
                        } } else {
                        if( $business6replace18 = get_sub_field( '6_month_replacement_18', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$business6replace18, 2, '.', '').'</td>';
                    } else if($business618 = get_sub_field( '6_month_18', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice186, 2, '.', '').'</td>';
                    }

                echo '</tr>';

                }


                }

                    if( $switchon318 = get_sub_field( '3_month_18', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth18 = get_sub_field( 'contract_length_18', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth18 . '</td>';
                    }
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                
                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile18 . ' + 3 + 17</td>';
                    }
                    } else {
                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                    echo '<td>£' . $profile18 . ' + 3 + 17</td>';
                    } }
                    if( $mileage18 = get_sub_field( 'mileage_18', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage18) . ' Per Annum</td>';
                    }
    

            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 

                        if( $rep6active18 = get_sub_field( '6_month_replacement_18', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$rep183plusvat, 2, '.', '').'</td>';
                        } else {
                        print '<td class="price">£'.number_format((float)$personalprice183, 2, '.', '').'</td>';
                        } } else {
                        if( $business3replace18 = get_sub_field( '3_month_replacement_18', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$business3replace18, 2, '.', '').'</td>';
                    } else if($business318 = get_sub_field( '3_month_18', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice183, 2, '.', '').'</td>';
                    }

                echo '</tr>';

                }


                }

                if( $switchon118 = get_sub_field( '1_month_18', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth18 = get_sub_field( 'contract_length_18', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth18 . '</td>';
                    }
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                
                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile18 . ' + 1 + 17</td>';
                    }
                    } else {
                    if( $profile18 = get_sub_field( 'fee_18', $product->get_id() ) ) {
                    echo '<td>£' . $profile18 . ' + 1 + 17</td>';
                    } }
                    if( $mileage18 = get_sub_field( 'mileage_18', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage18) . ' Per Annum</td>';
                    }
            

            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 

                        if( $rep6active18 = get_sub_field( '6_month_replacement_18', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$rep181plusvat, 2, '.', '').'</td>';
                        } else {
                        print '<td class="price">£'.number_format((float)$personalprice181, 2, '.', '').'</td>';
                        } } else {
                        if( $business1replace18 = get_sub_field( '1_month_replacement_18', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$business1replace18, 2, '.', '').'</td>';
                    } else if($business1 = get_sub_field( '1_month_18', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice181, 2, '.', '').'</td>';
                    }

                echo '</tr>';

                }


                }

                endwhile;

                else :

                // no rows found

                endif;

            if( have_rows('24_month_profile') ):
            // loop through the rows of data
            while ( have_rows('24_month_profile') ) : the_row();
            // display a sub field value

            if( $businessturnon = get_sub_field( 'business', $product->get_id() ) ) {
                     $businessprice249 = $businessturnon;
                    }

            if( $turnon246 = get_sub_field( '6_month_replacement', $product->get_id() ) ) {
                     $rep246 = $turnon246;
                    }

            if( $turnon243 = get_sub_field( '3_month_replacement', $product->get_id() ) ) {
                     $rep243 = $turnon243;
                    }

            if( $turnon241 = get_sub_field( '1_month_replacement', $product->get_id() ) ) {
                     $rep241 = $turnon241;
                    }

            if( $turnonprofile = get_sub_field( 'fee', $product->get_id() ) ) {
                     $profile = $turnonprofile;
                    }


                $personalprice249                   = ($businessprice249 / 100) * 20 + $businessprice249;
                $businessprice246                   = ($businessprice249 * 32) / 29 + 3;
                $personalprice246                   = ($businessprice246 / 100) * 20 + $businessprice246;
                $rep246plusvat                      = ($rep246 / 100) * 20 + $rep246;
                $businessprice243                   = ($businessprice249 * 32) / 26 + 5;
                $personalprice243                   = ($businessprice243 / 100) * 20 + $businessprice243;
                $rep243plusvat                      = ($rep243 / 100) * 20 + $rep243;
                $businessprice241                   = ($businessprice249 * 32) / 23 + 7.5;              
                $personalprice241                   = ($businessprice241 / 100) * 20 + $businessprice241;
                $rep241plusvat                      = ($rep241 / 100) * 20 + $rep241;
                $personalprofile                    = ($profile / 100) * 20 + $profile;



                if( $switchon = get_sub_field( 'active', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth = get_sub_field( 'contract_length', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth . '</td>';
                    }
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                
                    if( $profile = get_sub_field( 'fee', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile . ' + 9 + 23</td>';
                    }
                    } else {
                    if( $profile = get_sub_field( 'fee', $product->get_id() ) ) {
                    echo '<td>£' . $profile . ' + 9 + 23</td>';
                    } }
                    if( $mileage = get_sub_field( 'mileage', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage) . ' Per Annum</td>';
                    }
            
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 

                    if( $personal249 = get_sub_field( 'personal', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$personal249, 2, '.', '').'<span class="lowprice">Lowest Price</span></td>';
                    } else {
                        print '<td class="price">£'.number_format((float)$personalprice249, 2, '.', '').'<span class="lowprice">Lowest Price</span></td>';
                    } } else {
                    if( $businessprice249 = get_sub_field( 'business', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice249, 2, '.', '').'<span class="lowprice">Lowest Price</span></td>';
                    }
                    if($businessprice249 == 0) {
                        echo '<td class="price">CALL</td>';
                    } 

                echo '</tr>';

                }


                }


                if( $switchon6 = get_sub_field( '6_month', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth = get_sub_field( 'contract_length', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth . '</td>';
                    }
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                
                    if( $profile = get_sub_field( 'fee', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile . ' + 6 + 23</td>';
                    }
                    } else {
                    if( $profile = get_sub_field( 'fee', $product->get_id() ) ) {
                    echo '<td>£' . $profile . ' + 6 + 23</td>';
                    } }
                    if( $mileage = get_sub_field( 'mileage', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage) . ' Per Annum</td>';
                    }
            
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 

                        if( $rep6active = get_sub_field( '6_month_replacement', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$rep246plusvat, 2, '.', '').'</td>';
                        } else {
                        print '<td class="price">£'.number_format((float)$personalprice246, 2, '.', '').'</td>';
                        } } else {
                        if( $business6replace = get_sub_field( '6_month_replacement', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$business6replace, 2, '.', '').'</td>';
                    } else if($turnon6 = get_sub_field( '6_month', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice246, 2, '.', '').'</td>';
                    }
                        if($businessprice246 == 0) {
                        echo '<td class="price">CALL</td>';
                    } 

                echo '</tr>';

                }


                }

                    if( $switchon3 = get_sub_field( '3_month', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth = get_sub_field( 'contract_length', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth . '</td>';
                    }
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                
                    if( $profile = get_sub_field( 'fee', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile . ' + 3 + 23</td>';
                    }
                    } else {
                    if( $profile = get_sub_field( 'fee', $product->get_id() ) ) {
                    echo '<td>£' . $profile . ' + 3 + 23</td>';
                    } }
                    if( $mileage = get_sub_field( 'mileage', $product->get_id() ) ) {
                    echo '<td>£' . number_format($mileage) . ' Per Annum</td>';
                    }
    

            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 

                        if( $rep6active = get_sub_field( '6_month_replacement', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$rep243plusvat, 2, '.', '').'</td>';
                        } else {
                        print '<td class="price">£'.number_format((float)$personalprice243, 2, '.', '').'</td>';
                        } } else {
                        if( $business3replace = get_sub_field( '3_month_replacement', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$business3replace, 2, '.', '').'</td>';
                    } else if($turnon = get_sub_field( '3_month', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice243, 2, '.', '').'</td>';
                    } else if($businessprice243 == 5 ||  $personalprice243 == 6.00) {
                        echo '<td class="price">CALL</td>';
                    }

                echo '</tr>';

                }


                }

                if( $switchon1 = get_sub_field( '1_month', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth = get_sub_field( 'contract_length', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth . '</td>';
                    }
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                
                    if( $profile = get_sub_field( 'fee', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile . ' + 1 + 23</td>';
                    }
                    } else {
                    if( $profile = get_sub_field( 'fee', $product->get_id() ) ) {
                    echo '<td>£' . $profile . ' + 1 + 23</td>';
                    } }
                    if( $mileage = get_sub_field( 'mileage', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage) . ' Per Annum</td>';
                    }
            

            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 

                        if( $rep1active = get_sub_field( '1_month_replacement', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$rep241plusvat, 2, '.', '').'</td>';
                        } else {
                        print '<td class="price">£'.number_format((float)$personalprice241, 2, '.', '').'</td>';
                        } } else {
                        if( $business1replace = get_sub_field( '1_month_replacement', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$business1replace, 2, '.', '').'</td>';
                    } else if($business1 = get_sub_field( '1_month', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice241, 2, '.', '').'</td>';
                    }

                echo '</tr>';

                }


                }

                endwhile;

                else :

                // no rows found

                endif;

                                    // End of 24 month contract payment profile // 
            //start of 30 month payment profile//
            if( have_rows('30_month_profile') ):
            // loop through the rows of data
            while ( have_rows('30_month_profile') ) : the_row();
            // display a sub field value

            if( $businessturnon = get_sub_field( 'business_30', $product->get_id() ) ) {
                     $businessprice309 = $businessturnon;
                    }

            if( $turnon306 = get_sub_field( '6_month_replacement_30', $product->get_id() ) ) {
                     $rep306 = $turnon246;
                    }

            if( $turnon303 = get_sub_field( '3_month_replacement_30', $product->get_id() ) ) {
                     $rep303 = $turnon243;
                    }

            if( $turnon301 = get_sub_field( '1_month_replacement_30', $product->get_id() ) ) {
                     $rep301 = $turnon241;
                    }

            if( $turnonprofile = get_sub_field( 'fee_30', $product->get_id() ) ) {
                     $profile = $turnonprofile;
                    }

                $personalprice309                   = ($businessprice309 / 100) * 20 + $businessprice309;
                $businessprice306                   = ($businessprice309 * 39 / 36) + 3;
                $personalprice306                   = ($businessprice306 / 100) * 20 + $businessprice306;
                $rep306plusvat                      = ($rep306 / 100) * 20 + $rep306;
                $businessprice303                   = ($businessprice309 * 39 / 33) + 5;
                $personalprice303                   = ($businessprice303 / 100) * 20 + $businessprice303;
                $rep303plusvat                      = ($rep303 / 100) * 20 + $rep303;
                $businessprice301                   = ($businessprice309 * 39 / 30) + 7.5;              
                $personalprice301                   = ($businessprice301 / 100) * 20 + $businessprice301;
                $rep301plusvat                      = ($rep301 / 100) * 20 + $rep301;
                $personalprofile30                  = ($profile / 100) * 20 + $profile;



                if( $switchon = get_sub_field( 'active_30', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth30 = get_sub_field( 'contract_length_30', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth30 . '</td>';
                    }
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                
                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile . ' + 9 + 29</td>';
                    }
                    } else {
                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                    echo '<td>£' . $profile30 . ' + 9 + 29</td>';
                    } }
                    if( $mileage30 = get_sub_field( 'mileage_30', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage30) . ' Per Annum</td>';
                    }
            
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 

                    if( $personal249 = get_sub_field( 'personal_30', $product->get_id() ) ) {
                        print '<td class="price">>£'.number_format((float)$personal309, 2, '.', '').'<span class="lowprice">Lowest Price</span></td>';
                    } else {
                        print '<td class="price">£'.number_format((float)$personalprice309, 2, '.', '').'<span class="lowprice">Lowest Price</span></td>';
                    } } else {
                    if( $business249 = get_sub_field( 'business_30', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice309, 2, '.', '').'<span class="lowprice">Lowest Price</span></td>';
                    } 

                echo '</tr>';

                }


                }


                if( $switchon630 = get_sub_field( '6_month_30', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth30 = get_sub_field( 'contract_length_30', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth30 . '</td>';
                    }
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                
                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile30 . ' + 6 + 29</td>';
                    }
                    } else {
                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                    echo '<td>£' . $profile30 . ' + 6 + 29</td>';
                    } }
                    if( $mileage30 = get_sub_field( 'mileage_30', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage30) . ' Per Annum</td>';
                    }
            
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 

                        if( $rep6active30 = get_sub_field( '6_month_replacement_30', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$rep306plusvat, 2, '.', '').'</td>';
                        } else {
                        print '<td class="price">£'.number_format((float)$personalprice306, 2, '.', '').'</td>';
                        } } else {
                        if( $business6replace30 = get_sub_field( '6_month_replacement_30', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$business6replace30, 2, '.', '').'</td>';
                    } else if($business630 = get_sub_field( '6_month_30', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice306, 2, '.', '').'</td>';
                    }

                echo '</tr>';

                }


                }

                    if( $switchon330 = get_sub_field( '3_month_30', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth30 = get_sub_field( 'contract_length_30', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth30 . '</td>';
                    }
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                
                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile30 . ' + 3 + 29</td>';
                    }
                    } else {
                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                    echo '<td>£' . $profile30 . ' + 3 + 29</td>';
                    } }
                    if( $mileage30 = get_sub_field( 'mileage_30', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage30) . ' Per Annum</td>';
                    }
    

            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 

                        if( $rep6active30 = get_sub_field( '6_month_replacement_30', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$rep303plusvat, 2, '.', '').'</td>';
                        } else {
                        print '<td class="price">£'.number_format((float)$personalprice303, 2, '.', '').'</td>';
                        } } else {
                        if( $business3replace30 = get_sub_field( '3_month_replacement_30', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$business3replace30, 2, '.', '').'</td>';
                    } else if($business330 = get_sub_field( '3_month_30', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice303, 2, '.', '').'</td>';
                    }

                echo '</tr>';

                }


                }

                if( $switchon130 = get_sub_field( '1_month_30', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth30 = get_sub_field( 'contract_length_30', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth30 . '</td>';
                    }
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                
                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile30 . ' + 1 + 29</td>';
                    }
                    } else {
                    if( $profile30 = get_sub_field( 'fee_30', $product->get_id() ) ) {
                    echo '<td>£' . $profile30 . ' + 1 + 29</td>';
                    } }
                    if( $mileage30 = get_sub_field( 'mileage_30', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage30) . ' Per Annum</td>';
                    }
            

            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 

                        if( $rep6active30 = get_sub_field( '6_month_replacement_30', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$rep301plusvat, 2, '.', '').'</td>';
                        } else {
                        print '<td class="price">£'.number_format((float)$personalprice301, 2, '.', '').'</td>';
                        } } else {
                        if( $business1replace30 = get_sub_field( '1_month_replacement_30', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$business1replace30, 2, '.', '').'</td>';
                    } else if($business1 = get_sub_field( '1_month_30', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice301, 2, '.', '').'</td>';
                    }

                echo '</tr>';

                }


                }

                endwhile;

                else :

                // no rows found

                endif;   

            //start of 36 month payment profile//
            if( have_rows('36_month_profile') ):
            // loop through the rows of data
            while ( have_rows('36_month_profile') ) : the_row();
            // display a sub field value

            if( $businessturnon = get_sub_field( 'business_36', $product->get_id() ) ) {
                     $businessprice369 = $businessturnon;
                    }

            if( $turnon366 = get_sub_field( '6_month_replacement_36', $product->get_id() ) ) {
                     $rep366 = $turnon366;
                    }

            if( $turnon363 = get_sub_field( '3_month_replacement_36', $product->get_id() ) ) {
                     $rep363 = $turnon363;
                    }

            if( $turnon361 = get_sub_field( '1_month_replacement_36', $product->get_id() ) ) {
                     $rep361 = $turnon361;
                    }

            if( $turnonprofile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                     $profile36 = $turnonprofile36;
                    }

                $personalprice369                   = ($businessprice369 / 100) * 20 + $businessprice369;
                $businessprice366                   = ($businessprice369 * 45 / 42) + 3;
                $personalprice366                   = ($businessprice366 / 100) * 20 + $businessprice366;
                $rep366plusvat                      = ($rep366 / 100) * 20 + $rep366;
                $businessprice363                   = ($businessprice369 * 45 / 39) + 5;
                $personalprice363                   = ($businessprice363 / 100) * 20 + $businessprice363;
                $rep363plusvat                      = ($rep363 / 100) * 20 + $rep363;
                $businessprice361                   = ($businessprice369 * 45 / 36) + 7.5;              
                $personalprice361                   = ($businessprice361 / 100) * 20 + $businessprice361;
                $rep361plusvat                      = ($rep361 / 100) * 20 + $rep361;
                $personalprofile36                  = ($profile / 100) * 20 + $profile;



                if( $switchon = get_sub_field( 'active_36', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth36 = get_sub_field( 'contract_length_36', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth36 . '</td>';
                    }
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 
                
                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile36 . ' + 9 + 35</td>';
                    }
                    } else {
                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                    echo '<td>£' . $profile36 . ' + 9 + 35</td>';
                    } }
                    if( $mileage36 = get_sub_field( 'mileage_36', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage36) . ' Per Annum</td>';
                    }
            
            if (strpos($url2,'personal') || (isset($_SESSION["personal"])))  { 

                    if( $personal249 = get_sub_field( 'personal_36', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$personal369, 2, '.', '').'<span class="lowprice">Lowest Price</span></td>';
                    } else {
                        print '<td class="price">£'.number_format((float)$personalprice369, 2, '.', '').'<span class="lowprice">Lowest Price</span></td>';
                    } } else {
                    if( $business249 = get_sub_field( 'business_36', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice369, 2, '.', '').'<span class="lowprice">Lowest Price</span></td>';
                    } 

                echo '</tr>';

                }


                }


                if( $switchon636 = get_sub_field( '6_month_36', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth36 = get_sub_field( 'contract_length_36', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth36 . '</td>';
                    }
                if(strpos($url2,'personal')) {
                
                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile36 . ' + 6 + 35</td>';
                    }
                    } else {
                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                    echo '<td>£' . $profile36 . ' + 6 + 35</td>';
                    } }
                    if( $mileage36 = get_sub_field( 'mileage_36', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage36) . ' Per Annum</td>';
                    }
            
                    if (strpos($url2,'personal')) {

                        if( $rep6active36 = get_sub_field( '6_month_replacement_36', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$rep366plusvat, 2, '.', '').'</td>';
                        } else {
                        print '<td class="price">£'.number_format((float)$personalprice366, 2, '.', '').'</td>';
                        } } else {
                        if( $business6replace36 = get_sub_field( '6_month_replacement_36', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$business6replace36, 2, '.', '').'</td>';
                    } else if($business636 = get_sub_field( '6_month_36', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice366, 2, '.', '').'</td>';
                    }

                echo '</tr>';

                }


                }

                    if( $switchon336 = get_sub_field( '3_month_36', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth36 = get_sub_field( 'contract_length_36', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth36 . '</td>';
                    }
                if(strpos($url2,'personal')) {
                
                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile36 . ' + 3 + 35</td>';
                    }
                    } else {
                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                    echo '<td>£' . $profile36 . ' + 3 + 35</td>';
                    } }
                    if( $mileage36 = get_sub_field( 'mileage_36', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage36) . ' Per Annum</td>';
                    }
    

                if (strpos($url2,'personal')) {

                        if( $rep6active36 = get_sub_field( '6_month_replacement_36', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$rep363plusvat, 2, '.', '').'</td>';
                        } else {
                        print '<td class="price">£'.number_format((float)$personalprice363, 2, '.', '').'</td>';
                        } } else {
                        if( $business3replace36 = get_sub_field( '3_month_replacement_36', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$business3replace36, 2, '.', '').'</td>';
                    } else if($business336 = get_sub_field( '3_month_36', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice363, 2, '.', '').'</td>';
                    }

                echo '</tr>';

                }


                }

                if( $switchon136 = get_sub_field( '1_month_36', $product->get_id() ) ) {
                    echo '<tr>';
                
                    if( $contractlegnth36 = get_sub_field( 'contract_length_36', $product->get_id() ) ) {
                    echo '<td>' . $contractlegnth36 . '</td>';
                    }
                if(strpos($url2,'personal')) {
                
                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                    echo '<td>£' . $personalprofile36 . ' + 1 + 35</td>';
                    }
                    } else {
                    if( $profile36 = get_sub_field( 'fee_36', $product->get_id() ) ) {
                    echo '<td>£' . $profile36 . ' + 1 + 35</td>';
                    } }
                    if( $mileage36 = get_sub_field( 'mileage_36', $product->get_id() ) ) {
                    echo '<td>' . number_format($mileage36) . ' Per Annum</td>';
                    }
            

                    if (strpos($url2,'personal')) {

                        if( $rep6active36 = get_sub_field( '6_month_replacement_36', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$rep361plusvat, 2, '.', '').'</td>';
                        } else {
                        print '<td class="price">£'.number_format((float)$personalprice361, 2, '.', '').'</td>';
                        } } else {
                        if( $business1replace36 = get_sub_field( '1_month_replacement_36', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$business1replace36, 2, '.', '').'</td>';
                    } else if($business1 = get_sub_field( '1_month_36', $product->get_id() ) ) {
                        print '<td class="price">£'.number_format((float)$businessprice361, 2, '.', '').'</td>';
                    }

                echo '</tr>';

                }


                }

                endwhile;

                else :

                // no rows found

                endif;

            echo ' 
 



            </tbody>
                </table>
                           <p class="contracttncs">The rental quoted is commission free. Excess mileage charges will apply. This is a non-maintained contract and the responsibility is with the hirer to maintain the vehicle (driver maintained), at the main dealers as per the manufacturer&#96;s warranty guidelines. At the end of the contract you simply hand the vehicle back (subject to terms and conditions of your agreement).</p>
                </div>
                </div>';

}


function arphabet_widgets_init() {

    register_sidebar( array(
        'name'          => 'product search',
        'id'            => 'productsearch',
        'before_widget' => '<div>',
        'after_widget'  => '</div>',
        'before_title'  => '<h2 class="rounded">',
        'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
    'name' => 'Footer Sidebar 1',
    'id' => 'footer-sidebar-1',
    'description' => 'Appears in the footer area',
    'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    'after_widget' => '</aside>',
    'before_title' => '<h3 class="widget-title">',
    'after_title' => '</h3>',
) );

}
add_action( 'widgets_init', 'arphabet_widgets_init' );


/* find the hook on a page

$debug_tags = array();
add_action( 'all', function ( $tag ) {
    global $debug_tags;
    if ( in_array( $tag, $debug_tags ) ) {
        return;
    }
    echo "<pre>" . $tag . "</pre>";
    $debug_tags[] = $tag;
} );

*/


function do_test_shortcode() {

    echo 'this works!';

}

add_shortcode('testcode', 'do_text_shortcode');




// Edit WooCommerce dropdown menu item of shop page//
// Options: menu_order, popularity, rating, date, price, price-desc
 
function my_woocommerce_catalog_orderby( $orderby ) {
    unset($orderby["popularity"]);
    unset($orderby["rating"]);
    unset($orderby["date"]);

    return $orderby;
}
add_filter( "woocommerce_catalog_orderby", "my_woocommerce_catalog_orderby", 20 );


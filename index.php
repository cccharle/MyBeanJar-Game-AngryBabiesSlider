<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height, target-densitydpi=medium-dpi" />
        <title>Angry Babies</title>
        <!--    /////////////////       -->
        <!--    // STYLESHEETS //       -->
        <!--    /////////////////       -->
        <link rel="stylesheet" type="text/css" href="css/reset.css" media="all" />
        <link rel="stylesheet" type="text/css" href="css/sx_reset.css" />
        <link rel="stylesheet" type="text/css" href="css/mbj_capsule.css" />
        <link rel="stylesheet" type="text/css" href="css/mbjNotifier.css" media="all" />
        <link rel="stylesheet" type="text/css" href="css/global.css" media="all" />
        <link rel="stylesheet" type="text/css" href="css/devices.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="css/jquery.jqpuzzle.css" media="all" />
        <link rel="stylesheet" type="text/css" href="css/mxicoders.css" media="all" />
        <link rel="stylesheet" type="text/css" href="css/ionicons.css" media="all" />
        <link rel="stylesheet" type="text/css" href='//fonts.googleapis.com/css?family=Bangers'>
        <!--    ///////////////         -->
        <!--    //  SCRIPTS  //         -->
        <!--    ///////////////         -->
        <script type="text/javascript" charset="utf-8" src="lib/js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/jquery-ui.min.js"></script>     
        <script type="text/javascript" charset="utf-8" src="lib/fastclick/lib/fastclick.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/mbjNotifier.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/mbjCredentials.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/MBJRequest.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/apiRequest.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/md5.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/appsMethods.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/winnersMethods.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/sponsorsMethods.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/sponsorLocationMethods.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/beansMethods.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/categoriesMethods.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/awardMethods.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/registerMethods.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/sendpasswordMethods.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/deleteMethods.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/reedemMethods.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/authenticateuserMethods.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/validateuserMethods.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/imagebuy.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/mybeanjar/code/sdk/imageget.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/spin.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/jquery.ba-dotimeout.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/jquery.color-2.1.2.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/jquery-animate-css-rotate-scale.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/sxHelpers.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/jquery.jqpuzzle.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/slider/jssor.core.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/slider/jssor.utils.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/slider/jssor.slider.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/winners.js"></script>
        <script type="text/javascript" charset="utf-8" src="lib/js/slider.js"></script>
        <script src='https://www.paypalobjects.com/js/external/dg.js' type='text/javascript'></script>
        <script type="text/javascript">

            jQuery(document).ready(function() {
                initJQPuzzle();
                
                console.log("Puzzle ready.");
                //mbjAttemptAward();
                // get_winners("mainmenu");
                jQuery('#user-puzzle-page').hide();
                // WatchPurchaseOptions();
                mbjAttemptLogin();
                MbjCreateOnboardModal();



            });
        </script>
        <script type="text/javascript">                         // CLEAN UP LATER!!
            jQuery.preloadImages = function() {
                for (var i = 0; i < arguments.length; i++) {
                    $("<img />").attr("src", arguments[i]);
                }
            }
            jQuery.preloadImages("img/ab-sbubb-l-act.png", "img/ab-sbubb-r-act.png");
        </script>

        <!-- Hacky IAP button toggle fix -->
        <script>
          jQuery( 'div.iap-opt' ).click(function() {
            alert("!!");
            alert(jQuery( this ).id());
          });
        </script>
        <!-- facebook login script -->
        <script>
   
  // This is called with the results from from FB.getLoginStatus().
  function statusChangeCallback(response) {
    //console.log('statusChangeCallback');
    //console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
     

 AWS.config.region = 'us-east-1';
// Other service clients will automatically use the Cognito Credentials provider
// configured in the JavaScript SDK.
var cognitoSyncClient = new AWS.CognitoSync();
         AWS.config.credentials = new AWS.CognitoIdentityCredentials({
    AccountId: "979831593594",
    RoleArn: "arn:aws:iam::979831593594:role/S3Access",
    IdentityPoolId: "us-east-1:5839dc73-2ec7-4275-b493-632f1e6dd0bf",
  Logins: { // optional tokens, used for authenticated login
    'graph.facebook.com': response.authResponse.accessToken,
  }
});
AWS.config.credentials.get(function(err) {
    if (!err) {
        console.log("Cognito Identity Id: " + AWS.config.credentials.identityId);
    }
    else
    {
        console.log(err);
    }
});
//console.log(response.authResponse.accessToken);
s3 = new AWS.S3; 
//console.log(s3);
      // Logged into your app and Facebook.
      testAPI();
    } else if (response.status === 'not_authorized') {
      // The person is logged into Facebook, but not your app.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into this app.';
    } else {
      // The person is not logged into Facebook, so we're not sure if
      // they are logged into this app or not.
      document.getElementById('status').innerHTML = 'Please log ' +
        'into Facebook.';
    }
  }

  // This function is called when someone finishes with the Login
  // Button.  See the onlogin handler attached to it in the sample
  // code below.
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
  }

  window.fbAsyncInit = function() {
  FB.init({
    appId      : '1584290141823663',
    cookie     : true,  // enable cookies to allow the server to access 
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.2' // use version 2.2
  });

  // Now that we've initialized the JavaScript SDK, we call 
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });

  };

  // Load the SDK asynchronously
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));

  // Here we run a very simple test of the Graph API after login is
  // successful.  See statusChangeCallback() for when this call is made.
  function testAPI() {
    //console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
      console.log(response);
      $("#mbj_logintab").removeClass("active_selector");
      $("#mbj_logintab").addClass("inactive_selector");
      $("#mbj_registertab").addClass("active_selector");
      $("#mbj_registertab").removeClass("inactive_selector");
      $("#mbj_login").removeClass("active_pane");
      $("#mbj_login").addClass("inactive_pane");
      $("#mbj_register").removeClass("inactive_pane");
      $("#mbj_register").addClass("active_pane");
      var username = response.first_name+randomusername();
      var username = username.toLowerCase();
      var password = randompass();
      $("#mbj_form_reg_u").val(username);
      $("#mbj_form_reg_p").val(password);
      $("#mbj_form_reg_p2").val(password);
      $("#mbj_form_reg_email").val(response.email);
      $("#mbj_reg_zip").val(response.email);
    });
  }
  function randompass()
  {
       var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
        var string_length = 8;
        var randomstring = '';
        for (var i=0; i<string_length; i++) {
            var rnum = Math.floor(Math.random() * chars.length);
            randomstring += chars.substring(rnum,rnum+1);
        }
        return randomstring;
  }
  function randomusername()
  {
       var chars = "ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
        var string_length = 4;
        var randomstring = '';
        for (var i=0; i<string_length; i++) {
            var rnum = Math.floor(Math.random() * chars.length);
            randomstring += chars.substring(rnum,rnum+1);
        }
        return randomstring;
  }
  function testpostedimg()
{
    if (mbjUserLoggedIn) {
        get_user_image(u, p, mbjAppID, testpostedimage);   
    }

    else {
        mbjAttemptLogin();
    }
}
function testpostedimage(result, message)
{
    uimg = [];
    var imagestr = '';
    
    for (var i = 0; i < message.length; i++)
    {
        imagestr = imagestr+message[i].image;   
    }
    var strhtml = '';
    $("#listed8images").empty();
     <?php
        $dir = "img/puzz/";
        if ($dir = opendir('img/puzz/')) {
            $images = array();
            while (false !== ($file = readdir($dir))) {
                if ($file != "." && $file != "..") {
                    $images[] = $file;
                }
            }
            closedir($dir);
        }

        for ($i = 0; $i < count($images); $i++) {
            ?>
                    var imagename = '<?php echo $images[$i] ?>';
        var n = imagestr.indexOf(imagename);   
        var imagepath = "'img/puzz/<?php echo $images[$i] ?>'";
            if(n != "-1")
            {
                var checked = "checked='checked' disabled='disabled'";
                var clickhere = 'onclick="javascript:getimageforplay(this)"';
                
            }
            else
            {
                var checked = '';
                var clickhere = 'onclick="javascript:getimage('+imagepath+')"';
                
            }
            
           // imagepath = imagepath+imagename;
            strhtml = strhtml+'<li><div class="iap-img"><img src="img/puzz/<?php echo $images[$i] ?>" '+clickhere+' ><span>TITLE ARTIST</span>'+
                          '<input '+checked+' id="radio1" type="checkbox" name="radio[]" value="<?php echo $images[$i] ?>"  >'+
                          '<label for=""><span><span></span></span></label></div></li>';
    
        
        <?php } ?>    
            $("#listed8images").append(strhtml);
    
    
    
}
</script>


<script src="https://sdk.amazonaws.com/js/aws-sdk-2.1.30.min.js"></script>
    </head>

    <body>
        <div class="ab-bg-container" id="bg-container-outer">
            <img class="ab-bg-accents" id="accent-upperright" src="img/corner-accent-upperright002.png">
            <img class="ab-bg-accents" id="accent-lowerleft" src="img/corner-accent-lowerleft002.png">
        </div>
        <div id="app">
            <div class="ab-bg-container" id="bg-container">
                <img class="ab-bg-accents" id="accent-upperright" src="img/corner-accent-upperright002.png">
                <img class="ab-bg-accents" id="accent-lowerleft" src="img/corner-accent-lowerleft002.png">
            </div>




            <!--            Home screen             -->
            <div id="title-page" data-role="page" class="pg-page">
                <div data-role="content" class="content">
                    <script type="text/javascript">                         // CLEAN UP LATER!!
                        jQuery(document).ready(function() {
                            jQuery("#intro01").delay(3000).fadeOut("slow", function() {
                                jQuery("#intro02").delay(1000).fadeIn("slow");
                            });
                        });
                    </script>
                    <!-- flash screen -->
                    <div id="intro01" class="intro">
                        <div class="title-bits">
                            <img class="title-element start-large-logo" src="img/mbj-logo.png">
                        </div>
                    </div>
                    <div id="intro02" class="intro">
                        <div class="title-bits"> 
                            <div class="start-ab-logo">
                                <img class="title-element start-large-logo" src="img/ab-logo.png">
                            </div>
                            <p class="start-cbldf-descrip">ANGRY BABIES is a slider puzzle game, developed by MYBEANJAR, in conjunction with the COMIC BOOK LEGAL DEFENSE FUND.</p>
                            <div class="start-cbldf">
                            
                                <img class="title-element sm-logo" src="img/cbldf-logo.png">
                                
                                <img class="title-element sm-logo" src="img/mbj-logo-base.png">
                            </div>
                            <div class="title-element button-pool start-buttons">
                                <button class="btn-ab btn-ab-r" onclick="javascript:play();">Play</button>
                                <button class="btn-ab btn-ab-l" onclick="javascript:info()">Info</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <!--            INFO PAGE             -->

            <div id="info-page" data-role="page" class="pg-page">
                <div data-role="content" class="content">
                    
                    <div class="info-nav">
                      <form id="info-close">
                        <button type="submit" class="btn-back" id="info-close-submit">
                          <!-- <span class="nav-button">&lt; Back</span>  --><img src="img/ui_action_back.png">
                        </button>
                      </form>
                    </div>

                    <div class="info-container">
                      <h1 class="info-title">Info</h1>
                      <img class="comicscode" src="img/comicscode.png" />
                      <p class="basic-para"><a href="/" target="_blank">Angry Babies</a> is a sliding-tile puzzle game developed by <a href="http://www.mybeanjar.com/" target="_blank">MyBeanJar</a>, in conjunction with the <a href="http://cbldf.org/" target="_blank">Comic Book Legal Defense Fund</a>.</p>
                      <p class="basic-para">Various original Angry Baby characters were created for this puzzle by top illustrators in the comic book field. In support of the CBLDF, players can purchase any or all Angry Baby designs, which are scrambled into the puzzle.</p>
                      <p class="basic-para">Plus all purchases include a free Matching Angry Baby Collector Poster.</p>
                      <p class="basic-para">For more info and previews check out the <a href="http://angrybabies.mybeanjar.com/" target="_blank">Angry Babies Blog</a>.</p>
                      <div class="info-cbldf">
                                <div class="info-logo"><a href="http://cbldf.org/" target="_blank"><img class="title-element sm-logo info-logo" src="img/cbldf-logo.png"></a></div>
                                <div class="info-logo"><a href="http://www.mybeanjar.com/" target="_blank"><img class="title-element sm-logo info-logo" src="img/mbj-logo-base.png"></a></div>
                      </div>
                      <!-- <br/>
                      <br/> -->
                      <p class="basic-para">To win deals &amp; freebies playing this and other games <a href="/">sign up for MyBeanJar</a>.</p>
                      <br/>
                      <h1 class="info-title">Thanks to the following contributors:</p>
                      <p class="basic-para">Josh Beatman - Brainchild Studios/NYC</p>
                      <p class="basic-para">Christopher Charles - <a href="http://subplex.com" target="_blank">subplex</a></p>
                      <p class="basic-para">Todd Klein - <a href="http://kleinletters.com" target="_blank">KleinLetters.com</a></p>


                    </div>
                </div>
            </div>





<!--              <div id="info-page" data-role="page" class="pg-page">
                <div data-role="content" class="content">
                    <div class="info-page">  
                        <div class="top-img-all">
                            <span><img width="33%" class="pull-left" src="img/sm-logo.png" onclick="javascript:gotohome()"></span>
                            <span class="pull-right"> <img width="33%" src="img/close.png" class="pull-right" onclick="javascript:gotohome();" ></span>
                        </div>
                        <button class="btn-ab" onclick="javascript:gotohome()">Home</button>
                        <p class="page-title-info">Info</p>
                        <ul class="listing-info">
                            <li>Angry Babies</li>
                            <li>CBLDF- About, link</li>
                            <li>MBJ- About, link</li>
                            <li>Credits</li>
                        </ul>
                    </div>
                </div>
            </div> -->
            <!--           Display all 8 images             -->
            <div id="listing-page" data-role="page" class="pg-page">
                <div data-role="content" class="content ">
                    <div  class="intro scrool">
                        <div class="listing-page-custom">
                            <div>
                                <!--<button class="btn-ab" onclick="javascript:gotohome()">Home</button>-->
                                <div id="listing-descrip">
                                  <h2 class="ab-title">ANGRY BABIES NEED TO PLAY!</h2>
                                  <p class="listing-blurb">EACH PURCHASE INCLUDES A FREE COLLECTOR POSTER</p>
                                  <p class="ab-title">99¢ EACH- TAKE EM ALL</p>
                                </div>
                            </div>
                            <ul class="listing-listing btn-ab-iap"><div class="example" >
                                 <!-- <button class="btn-ab btn-ab-r btn" onclick="javascript:freeplay()">FREE PLAY</button> -->
                                 
                                <!-- FREE PLAY BUTTON -->
                                <li>
                                  <div class="iap-opt btn-ab-iap-flextainer" id="iap-opt-0">
                                    <img src="img/random-img.png" onclick="javascript:freeplay()">
                                    <div class="metadata">
                                      <span>RANDOM IMAGE</span>
                                    </div>
                                    <div class="iap-btn-container">
                                      <button class="btn-mbj btn-iap" style="display: none;">
                                        <span><i class="ion-android-arrow-dropright-circle" style="vertical-align: middle; visibility: hidden;"></i></span>
                                      </button>
                                      <button class="btn-mbj btn-iap btn-play" onclick="javascript:freeplay()">
                                        <span><i class="ion-android-arrow-dropright-circle" style="vertical-align: middle;"></i><br/>play</span>
                                      </button>
                                      <input id="radio1[]" class="iap-check" type="checkbox" name="radio[]" style="display: none;" value="random-img.png">
                                    </div>
                                    
                                    <span class="unused">
                                      <span class="unused">
                                      </span>
                                    </span>
                                  </div>
                                </li>


                                 <div id="listed8images">
                                  <?php
                                    $dir = "img/puzz/";
                                    if ($dir = opendir('img/puzz/')) {
                                        $images = array();
                                        while (false !== ($file = readdir($dir))) {
                                            if ($file != "." && $file != "..") {
                                                $images[] = $file;
                                            }
                                        }
                                        closedir($dir);
                                    }

                                    for ($i = 0; $i < count($images); $i++) {
                                  ?>
            
            <li><div class="iap-opt btn-ab-iap-flextainer" id="iap-opt-<?php echo $i ?>">
                    <img src="img/puzz/<?php echo $images[$i] ?>" onclick="javascript:getimage('img/puzz/<?php echo $images[$i] ?>')">
                    <div class="metadata">
                      <span>TITLE ARTIST</span>
                    </div>
                    <div class="iap-btn-container">
                      <button class="btn-mbj btn-iap btn-info" onclick="javascript:PreviewImage(this)">
                        <span><i class="ion-information-circled" style="vertical-align: middle;"></i><br/>preview</span>
                      </button>
                      <button class="btn-mbj btn-iap btn-purchase" onclick="javascript:AddToCart(this)">
                        <span><i class="ion-android-add-circle" style="vertical-align: middle;"></i><br/>$0.99</span>
                      </button>
                      <input id="radio1[]" class="iap-check" style="display: none"type="checkbox" name="radio[]" value="<?php echo $images[$i] ?>"  >
                    </div>
                    
                    <span class="unused"><span class="unused"></span></span></div></li>
        <?php } ?>      
                                        
                                 </div> </div></ul>
                            
                            
                            <button type="submit" class="btn-mbj btn-ab-buy submit full-width" id="image-selector"  onclick="javascript:purchaseclickimage()">
                                <span><i class="ion-ios-cart-outline" style="vertical-align: middle; padding-right:0.5em;"></i>Purchase</span>
                            </button>

                            <!-- <button class="btn-ab btn listing-btn-auto" onclick="javascript:purchaseclickimage()">PURCHASE</button> -->
                          </div>
                    </div>
                </div>
            </div>
            <form action="https://www.paypal.com/cgi-bin/webs" target="PPDGFrame" class="standard">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="P38PFY6CF8VF2">
                <input type="hidden" name="custom" id="customeid" value="">
                <input type="hidden" name="quantity" id="quantity" value="">
                <input type="hidden" name="cancel_return" value="http://mbdbtechnology.com/projects/ab/app/paypal/cancel.php">
                <input type="hidden" name="return" value="http://mbdbtechnology.com/projects/ab/app/paypal/success.php">
                <input type="hidden" name="notify_url" value="http://mbdbtechnology.com/projects/ab/app/paypal/ipn.php"> 
                <input type="image" name="submit" value="Buy"  border="0" name="submit" id="paypal_submit" alt="PayPal - The safer, easier way to pay online!" style="display: none" >
            </form>
            




            <!--            Image preview page on thumbnail click             -->
            <div id="image-preview" data-role="page" class="pg-page">
                <div data-role="content" class="content">    
                    <div class="pop-page-custom">
                        <!--<button class="btn-ab" onclick="javascript:gotohome()">Home</button>-->
                        <div class="preview-nav">
                          <span><img width="50%" class="pull-left" src="img/angry-baby.png" onclick="javascript:gotohome()"></span>
                          <form id="preview-close">
                            <button type="submit" class="btn-preview-close" id="preview-close-submit">
                              <img src="img/ui_action_close.png">
                            </button>
                          </form>
                        </div>

 <!--                        <div class="top-img-all">
                            <span><img width="50%" class="pull-left" src="img/angry-baby.png" onclick="javascript:gotohome()"></span>
                            <span class="pull-right"> <img width="33%" src="img/close.png" class="pull-right" onclick="javascript:play();" ></span>
                        </div> -->
                        <div class="popup" id="load-preview-image">
                            <img src="img/img-preview.png" class="img-center-new">
                        </div>
                        <div class="des-info-new">
                            <!-- <p class="small-text-white">TITLE NAME</p>
                            <p class="small-text-white">ARTIST NAME</p>
                            <p class="small-text-white">PROFILE TEXT XOXONN DJ<br>SJS FKLLJHIND KS JD AJ A<br>DJS J LNK HHH H D AHS</p> -->
                            <p class="title">Marineman</p>
                            <p class="artist">Ian Churchill</p>
                            <p class="description">The all ages, Eisner Award nominated series and graphic novel—now as an angry baby!</p>
                        </div>
<!--                         <center class="top-minue">
                            <img src="img/PurchasedImages.png" onclick="javascript:mbjBuyUserimage()" />
                        </center> -->
                    </div>
                </div>
            </div>
            




            <!--            free puzzle play page             -->
            <div id="puzzle-page" data-role="page" class="pg-page">
                <div data-role="header" data-id="header" id="header" data-position="fixed" class="ui-header">
                </div>
                <div data-role="content" class="content content-puzzle">
<!--                    <button class="btn-ab" onclick="javascript:gotohome()">Home</button>-->
                    <div class="container" id="puzz-outer">
                        <div class="container puzz-element" id="puzzle-container">
                            <!-- Pulls random puzzle image from the img/puzz directory -->
                            <?php
                            $dir = "img/puzz/";
                            $imgpool = scandir($dir);
                            $randomimg = rand(2, sizeof($imgpool) - 1);
                            ?>
                            <div id="hintloader">
                                <img src="img/activind.gif">
                            </div>
                            <img class="jqPuzzle jqp-r3-c3-h1-SNABCDE" id="puzzImg" src="img/puzz/<?php echo $imgpool[$randomimg]; ?>">
                        </div>
                    </div>
                    <div class="button-pool">
                        <button class="btn-ab btn-ab-r" onclick="javascript:updateHint()">Hint</button>
                    </div>
                    <div class="mbj-footer">
                        <div class="footer-header-strip">
                            <img class="grippy" src="img/grippybit.png">
                            <span class="footer-header-strip-counter count_wins">Thousands of Beans awarded</span>
                        </div>
                        <div id="main_container" class="panel_container">
                            <div class="slider_temp">
                                <div id="slider1_container">
                                    <div u="slides" id="slider_components">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-signup-strip">
                            <div id="mbj-footer-logo">
                                <a href="http://mybeanjar.com" target="_blank"><img src="img/mbj_logo_sm_50px.png" id="footer-logo"></a>
                            </div>
                            <div id="mbj-footer-cta">
                                <div id="mbj-footer-cta-sub">
                                    <span class="footer-cta-title">Play, Win, Cash-In!</span>
                                    <button id="footer-signup" onclick="javascript:mbjAttemptAward()">Sign Up</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- BEAN NOTIFICATION WINDOW -->
                    <div class="mbj_notification bean_notification_window">
                        <img class="bean_notification_image" src="">
                        <p class="bean_notification_text">...</p>
                    </div>
                    <!-- BEAN AWARD CAPSULE MODAL -->
                    <div class="mbj_notification mbj_capsule_container autocentered hidden">
                        <div class="mbj_capsule mbj_anim_capsule_init">
                            <img class="mbj_capsule_body" src="img/mbj_capsule_body.png">
                            <img class="mbj_capsule_lid" src="img/mbj_capsule_lid.png">
                            <div class="mbj_capsule_payload mbj_anim_halfscale">
                                <img class="mbj_capsule_payload_contents" id="mbj_payload_overlay" src="img/mbj_payload_overlay.jpg">
                                <img class="mbj_capsule_payload_contents" id="mbj_award_img" src="img/mbj_payload_img.png">
                                <img class="mbj_capsule_glory" src="img/mbj_payload_glory.png">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- MBJ LOGIN/REGISTRATION MODAL -->
<!--             <div class="mbj_notification mbj_notification_container autocentered" id="mbj_notification_container_login">
              

              <form id="mbj_notification_close">
                  <button type="submit" class="btn-close" id="mbj_notification_close_submit">
                      <img src="img/ui_action_close.png">
                  </button>
              </form>
 -->

              <!-- INITIAL LOGIN VIEW -->
<!--               <div class="mbj_notification_inner" id="mbj-login-init">
                
                <div class="mbj_notification_title">
                    <img src="img/mbj_logo_50px.png" />
                    <h2>WIN REAL STUFF YOU CHOOSE</h2>
                </div>
                
                <div class="mbj_notification_beanticker">
                  <div id="main_container" class="panel_container">
                    <div class="slider_temp">
                        <div id="slider1_container_t">
                            <div u="slides" id="slider_components_t">
                            </div>
                        </div>
                    </div>
                  </div>
                </div>

                <div class="mbj_notification action-buttons">
                    
                    <button class="btn-mbj btn-facebook submit full-width" id="mbj-facebook-login">
                        <span class="mbj-button-icon"><i class="ion-social-facebook" style="font-size: 28px; vertical-align: middle; padding-right:0.5em;"></i></span><span class="mbj-button-text">Log in with Facebook</span>
                    </button>

                    <button class="btn-mbj submit full-width" id="mbj-login" onclick="MbjDisplayLoginView()">
                        <span class="mbj-button-icon"><i class="ion-log-in" style="font-size: 28px; vertical-align: middle; padding-right:0.5em;"></i></span><span class="mbj-button-text">Log in / sign up with MyBeanJar</span>
                    </button>

                </div>
              </div>
            </div> -->


            <!-- TEMPORARY STORAGE! -->

              <!-- INITIAL LOGIN VIEW -->
<!--               <form id="mbj_notification_close">
                  <button type="submit" class="btn-close" id="mbj_notification_close_submit">
                      <img src="img/ui_action_close.png">
                  </button>
              </form>

              <div class="mbj_notification_inner" id="mbj-login-init">

                
                <div class="mbj_notification_title">
                    <img src="img/mbj_logo_50px.png" />
                    <h2>WIN REAL STUFF YOU CHOOSE</h2>
                </div>
                
                <div class="mbj_notification_beanticker">
                </div>

                <div class="mbj_notification action-buttons">
                    
                    <button class="btn-mbj btn-facebook submit full-width" id="mbj-facebook-login">
                        <span class="mbj-button-icon"><i class="ion-social-facebook" style="font-size: 28px; vertical-align: middle; padding-right:0.5em;"></i></span><span class="mbj-button-text">Log in with Facebook</span>
                    </button>

                    <button class="btn-mbj submit full-width" id="mbj-login" onclick="MbjDisplayLoginView()">
                        <span class="mbj-button-icon"><i class="ion-log-in" style="font-size: 28px; vertical-align: middle; padding-right:0.5em;"></i></span><span class="mbj-button-text">Log in with MyBeanJar</span>
                    </button>

                </div> -->




              <!-- MBJ LOGIN VIEW -->    

<!--                 <div class="mbj_notification_inner" id="mbj-login-register">
                
                  <div class="mbj_notification_title">
                      <img src="img/mbj_logo_50px.png" />
                      <h2>SIGN IN AND WIN FOR REAL</h2>
                  </div>
                  

                  <div class="mbj-login mbj-form-block" id="mbj_login">
                      <form class="mbj-form" id="mbj_login_form" method="post">
                          <div class="element-input">
                              <label class="title" style="color: #a9a9a9;">username</label>
                              <input class="large" id="mbj_form_u" type="text" name="mbj_login" required/>
                          </div>
                          <div class="mbj_login_status">
                          </div>
                      </form>
                  </div>

                  <div class="submit action-buttons">
                      <button class="btn-mbj submit full-width" id="mbj-login" onclick="mbjAttemptAuthenticate()">
                          <span class="mbj-button-icon"><i class="ion-log-in" style="font-size: 28px; vertical-align: middle; padding-right:0.5em;"></i></span><span class="mbj-button-text">Log In</span>
                      </button>
                      <button class="btn-mbj submit full-width" id="mbj-login" onclick="MbjDisplayRegistrationView()">
                        <span class="mbj-button-icon"><i class="ion-android-clipboard" style="font-size: 28px; vertical-align: middle; padding-right:0.5em;"></i></span><span class="mbj-button-text">Sign Up</span>
                      </button>
                  </div>
                </div> -->



<!-- ?? -->
<!--                   <div class="mbj_notification mbj-form-block action-buttons">

                    <div class="mbj_notification_copy">
                      <p>Don't have a MyBeanJar account? Sign up now and win for real.</p>
                    </div>

                    <button class="btn-mbj submit full-width" id="mbj-login" onclick="MbjDisplayRegistrationView()">
                        <span class="mbj-button-icon"><i class="ion-android-clipboard" style="font-size: 28px; vertical-align: middle; padding-right:0.5em;"></i></span><span class="mbj-button-text">Sign Up</span>
                    </button>
                  </div>

                </div> -->





              <!-- REGISTRATION DETAILS VIEW -->

<!--                   <div class="mbj_notification_inner" id="mbj-login-details">
                  
                  <div class="mbj_notification_title">
                      <img src="img/mbj_logo_50px.png" />
                      <h2>SIGN IN AND WIN FOR REAL</h2>
                  </div>
                  

                  <div class="mbj-login mbj-form-block" id="mbj_details">
                      <form class="mbj-form" id="mbj_details_form" method="post" onsubmit:"return postTest();">



                          <div class="element-input">
                              <label class="title" style="color: #a9a9a9;">e-mail</label>
                              <input class="large" id="mbj_form_reg_email" type="email" name="mbj_reg_email" required/>
                          </div>
                          <div class="element-input">
                              <label class="title" style="color: #a9a9a9;">ZIP / postal code</label>
                              <input class="large" id="mbj_form_reg_zip" type="text" name="mbj_reg_zip" required/>
                          </div>
                          <div class="element-input">
                              <label class="title" style="color: #a9a9a9;">password</label>
                              <input class="large" id="mbj_form_p" type="password" name="mbj_password" required/>
                          </div>

                          <div class="mbj_login_status">
                          </div>
                      </form>
                  </div>

                  <div class="submit action-buttons">
                      
                      <button class="btn-mbj full-width" id="mbj-next" onclick="MbjDisplayCategoriesView()">
                          <span class="mbj-button-icon"><i class="ion-chevron-right" style="font-size: 28px; vertical-align: middle; padding-right:0.5em;"></i></span><span class="mbj-button-text">Next</span>
                      </button>

                  </div>
                </div> -->




              <!-- CATEGORY SELECTION VIEW -->
            
     <!--          <div class="mbj_notification_inner" id="mbj-login-categories">
                
                <div class="mbj_notification_title">
                    <img src="img/mbj_logo_50px.png" />
                    <h2>SIGN IN AND WIN FOR REAL</h2>
                </div>
                
                <div class="mbj-login mbj-form-block" id="mbj_categories">
                    <form class="mbj-form" id="mbj_categories_form" method="post">

                      <div class="mbj_notification_copy">
                        <p>What sort of rewards would you like to receive?</p>
                        <p>Select at least 3</p>
                      </div>

                      <div class="action-buttons category-list">
                          
                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-apparel" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Apparel</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-automotive" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Automotive</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-personalcare" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Personal Care</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-health" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Health &amp; Fitness</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-entertainment" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Entertainment</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-flowers" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Flowers &amp; Gifts</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-grocery" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Grocery or Packaged Goods</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-homegarden" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Home &amp; Garden</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-pets" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Pets</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-tech" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Tech</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-travel" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Travel &amp; Leisure</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-office" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Office</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-grabbag" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Grab Bag</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-charitable" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Charitable Donation</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-other" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Other</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-education" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Education</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                          <button type="button" class="btn-mbj btn-category full-width" id="mbj-category-games" onclick="MbjSelectCategory(this)">
                              <span class="mbj-button-text">Games</span><span class="mbj-button-icon"><i class="ion-android-radio-button-off"></i></span>
                          </button>

                        </div>

                        <div class="action-buttons">

                          <button class="btn-mbj submit full-width" id="mbj-login" onclick="MbjDisplayLoginView()">
                              <span class="mbj-button-icon"><i class="ion-android-send"></i></span><span class="mbj-button-text">Submit</span>
                          </button>

                        </div>
                    
                    </form>
                </div>
              </div> -->





















            <!-- [OLD] MBJ LOGIN/REGISTRATION MODAL -->
<!--             <div class="mbj_notification mbj_notification_container autocentered" id="mbj_notification_container_login">
              <div class="mbj_notification_inner">
                <div class="mbj_notification_title">
                    <img src="img/mbj_logo_50px.png" />
                    <h2>Play, Win, Cash In NOW</h2>
                </div>
                <div> -->
    <!--                 <fb:login-button scope="public_profile,email" onlogin="checkLoginState();">
                    </fb:login-button> -->
<!--                 </div>
                <div class="mbj_notification_copy">
                    <p>MyBeanJar scores great deals on stuff YOU want at the right time &amp; place. Real brands instantly, not points.</p>
                    <p>Win for Real Now!</p>
                </div>
                <form id="mbj_notification_close">
                    <button type="submit" class="btn-close" id="mbj_notification_close_submit">
                        <img src="img/ui_action_close.png">
                    </button>
                </form>
                <div class="mbj_notification_pane_selectors">
                    <div class="mbj_notification_pane_selector active_selector" id="mbj_logintab">
                        <h2>Login</h2>
                    </div>
                    <div class="mbj_notification_pane_selector inactive_selector" id="mbj_registertab">
                        <h2>Sign Up</h2>
                    </div>
                </div> -->

                <!--            login form             -->
<!--                 <div class="mbj_notification_pane mbj_login_or_register active_pane" id="mbj_login">
                    <form class="formoid-default-skyblue" id="mbj_login_form" method="post">
                        <div class="element-input">
                             <input class="large" id="mbj_form_u" type="text" name="mbj_login" required/>
                        </div>
                        <div class="element-input">
                            <label class="title" style="color: #a9a9a9;">password</label>
                            <input class="large" id="mbj_form_p" type="password" name="mbj_password" required/>
                        </div>
                        <div class="mbj_login_status">
                        </div>
                        <div class="submit">
                            <button type="submit" class="btn-mbj submit full-width" id="mbj_login_submit">
                                <span><i class="ion-log-in" style="font-size: 28px; vertical-align: middle; padding-right:0.5em;"></i>Login</span>
                            </button>
                        </div>
                    </form>
                </div> -->

                <!--            Register form            -->
<!--                 <div class="mbj_notification_pane mbj_login_or_register inactive_pane" id="mbj_register">
                    <form class="formoid-default-skyblue" id="mbj_registration_form" method="post">
                        <div class="element-input">
                            <label class="title" style="color: #a9a9a9;">username</label>
                            <input class="large" id="mbj_form_reg_u" type="text" name="mbj_reg_username" required/>
                        </div>
                        <div class="element-input">
                            <label class="title" style="color: #a9a9a9;">password</label>
                            <input class="large" id="mbj_form_reg_p" type="password" name="mbj_reg_password" required/>
                        </div>
                        <div class="element-input">
                            <label class="title" style="color: #a9a9a9;">confirm password</label>
                            <input class="large" id="mbj_form_reg_p2" type="password" name="mbj_reg_passconfirm" required/>
                        </div>
                        <div class="element-input">
                            <label class="title" style="color: #a9a9a9;">e-mail</label>
                            <input class="large" id="mbj_form_reg_email" type="email" name="mbj_reg_email" required/>
                        </div>
                        <div class="element-input">
                            <label class="title" style="color: #a9a9a9;">ZIP</label>
                            <input class="large" id="mbj_form_reg_zip" type="text" name="mbj_reg_zip" required/>
                        </div>
                        <div class="element-input">
                            <label class="title" style="color: #a9a9a9;">Bean reward categories (select 3)</label>
                            <input class="mbj_checkbox" type="checkbox" value="Apparel"><span class="form-checkbox">Apparel</span>
                            <input class="mbj_checkbox" type="checkbox" value="Automotive"><span class="form-checkbox">Automotive</span>
                            <input class="mbj_checkbox" type="checkbox" value="Food and Beverage"><span class="form-checkbox">Food and Beverage</span>
                            <input class="mbj_checkbox" type="checkbox" value="Personal Care"><span class="form-checkbox">Personal Care</span>
                            <input class="mbj_checkbox" type="checkbox" value="Health and Fitness"><span class="form-checkbox">Health and Fitness</span>
                            <input class="mbj_checkbox" type="checkbox" value="Entertainment"><span class="form-checkbox">Entertainment</span>
                            <input class="mbj_checkbox" type="checkbox" value="Flowers and Gifts"> <span class="form-checkbox">Flowers and Gifts</span>
                            <input class="mbj_checkbox" type="checkbox" value="Grocery or Packaged Goods"><span class="form-checkbox">Grocery or Packaged Goods</span>
                            <input class="mbj_checkbox" type="checkbox" value="Home and Garden"><span class="form-checkbox">Home and Garden</span>
                            <input class="mbj_checkbox" type="checkbox" value="Pets"><span class="form-checkbox">Pets</span>
                            <input class="mbj_checkbox" type="checkbox" value="Tech"><span class="form-checkbox">Tech</span>
                            <input class="mbj_checkbox" type="checkbox" value="Travel and Leisure"><span class="form-checkbox">Travel and Leisure</span>
                            <input class="mbj_checkbox" type="checkbox" value="Office"><span class="form-checkbox">Office</span>
                            <input class="mbj_checkbox" type="checkbox" value="Grab Bag"><span class="form-checkbox">Grab Bag</span>
                            <input class="mbj_checkbox" type="checkbox" value="Charitable Donation"><span class="form-checkbox">Charitable Domation</span>
                            <input class="mbj_checkbox" type="checkbox" value="Other"><span class="form-checkbox">Other</span>
                            <input class="mbj_checkbox" type="checkbox" value="Education"><span class="form-checkbox">Education</span>
                            <input class="mbj_checkbox" type="checkbox" value="Games"><span class="form-checkbox">Games</span>
                        </div>
                        <div class="mbj_login_status">
                        </div>
                        <div class="submit">
                            <button type="submit" class="btn-mbj submit full-width" id="mbj_registration_submit">
                                <span><i class="ion-clipboard" style="font-size: 28px; vertical-align: middle; padding-right:0.5em;"></i>Sign Up</span>
                            </button>
                        </div>
                    </form>
                </div>
              </div>
            </div> -->











            <!--           Display users purchased image            -->
            <div id="user-img-listing-page" data-role="page" class="pg-page">
<!--                <button class="btn-ab" onclick="javascript:gotohome()">Home</button>-->
                <div data-role="content" class="content ">
                    <div  class="intro scrool">
                        <div class="listing-page-custom">
                            <ul class="listing-listing">
                                <div class="example" id="userimages">
                                </div> 
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
             <!--            Selected image puzzle play page             -->
            <div id="user-puzzle-page" data-role="page" class="pg-page">
                <div data-role="header" data-id="header" id="header" data-position="fixed" class="ui-header">
                </div>
                <div data-role="content" class="content content-puzzle">
                    <div class="container" id="puzz-outer">
                        <div class="container puzz-element" id="puzzle-container">
                            <div id="hintloader" class="aaa">
                                <img src="img/activind.gif">
                            </div>                            
                        </div>
                    </div>
                    <div class="button-pool">
                        <button class="btn-ab btn-ab-r" onclick="javascript:updateHint()">Hint</button>
                    </div>
                   
                    <div class="mbj-footer">
                        <div class="footer-header-strip">
                            <img class="grippy" src="img/grippybit.png">
                            <span class="footer-header-strip-counter count_wins">Thousands of Beans awarded</span>
                        </div>
                        <div id="main_container" class="panel_container">
                            <div class="slider_temp">
                                <div id="slider1_container">
                                    <div u="slides" id="slider_components">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="footer-signup-strip">
                            <div id="mbj-footer-logo">
                                <a href="http://mybeanjar.com" target="_blank"><img src="img/mbj_logo_sm_50px.png" id="footer-logo"></a>
                            </div>
                            <div id="mbj-footer-cta">
                                <div id="mbj-footer-cta-sub">
                                    <span class="footer-cta-title">Play, Win, Cash-In!</span>
                                    <button id="footer-signup" onclick="javascript:mbjAttemptAward()">Sign Up</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- BEAN NOTIFICATION WINDOW -->
                    <div class="mbj_notification bean_notification_window">
                        <img class="bean_notification_image" src="">
                        <p class="bean_notification_text">...</p>
                    </div>
                    <!-- BEAN AWARD CAPSULE MODAL -->
                    <div class="mbj_notification mbj_capsule_container autocentered hidden">
                        <div class="mbj_capsule mbj_anim_capsule_init">
                            <img class="mbj_capsule_body" src="img/mbj_capsule_body.png">
                            <img class="mbj_capsule_lid" src="img/mbj_capsule_lid.png">
                            <div class="mbj_capsule_payload mbj_anim_halfscale">
                                <img class="mbj_capsule_payload_contents" id="mbj_payload_overlay" src="img/mbj_payload_overlay.jpg">
                                <img class="mbj_capsule_payload_contents" id="mbj_award_img" src="img/mbj_payload_img.png">
                                <img class="mbj_capsule_glory" src="img/mbj_payload_glory.png">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

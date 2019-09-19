<?php
    include 'database.php';

    //website, port  (try 80 or 443)

    if(!$sock = @fsockopen('www.google.com', 80))
    {
        echo 'Not Connected';
        exit;
    }
    else
    {
        ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <meta http-equiv="X-UA-Compatible" content="ie=edge">
                <title>Document</title>
                <!-- Compiled and minified CSS -->
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
                <!-- UIkit CSS -->
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.7/css/uikit.min.css" />

                        
            <style>
            .uk-search-default{
                width:450px;
            }
            </style>
            </head>
            <body>
            <?php
            
                date_default_timezone_set("Asia/kolkata");

                // API KEY
                $api_key = '417070a4214a89ed9e58266e92048125';

                // null city id 
                $city_id = '';
                
                if (isset($_POST['submit'])) {
                    $city_id = $_POST['city_name'];
                }
                else{
                    // by default rajkot city
                    $city_id = '1258847';
                }
                
                // city code
                $city_code = $city_id;

                $googleAPIurl = "https://api.openweathermap.org/data/2.5/weather?id=". $city_code ."&appid=". $api_key;

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_URL, $googleAPIurl);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_VERBOSE, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                $response = curl_exec($ch);

                curl_close($ch);

                $data = json_decode($response);

                $currentTime = time();
                
            ?>
                <div class="uk-section uk-section-small uk-section-muted">
                    <div class="uk-container">
                        <div class="uk-flex-center uk-child-width-1-1@m" uk-grid>
                            <div>
                                <div class="uk-card uk-card-default uk-card-small uk-card-body uk-width-1-1">
                                    <span style="font-size:35px; display:inline-block" class="uk-text-emphasis">Weather report</span>
                                    
                                    <hr>
                                    <div class="uk-align-right row ">                            
                                        <form action="index.php" method="post" id="select" class="uk-search uk-search-default">
                                            <div class="input-field col s9">
                                                <select name="city_name" id="city_name">
                                                    <option value="1258847" disabled selected>Choose your city</option>
                                                    <?php
                                                        $stmt = $con->prepare("SELECT * FROM `city` ORDER BY city_name ASC");
                                                        $stmt->execute();

                                                        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
                                                        $res = $stmt->fetchAll();
                                                    
                                                        foreach ($res as $key => $value) {
                                                            ?>
                                                            <option value="<?= $value['city_id'] ?>"><?= $value['city_name'] ?></option>
                                                            <?php
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class='col s3'>
                                                <button id="mybtn" class="btn waves-effect waves-light" type="submit" name="submit">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                    <h3 class="uk-card-title"><?= $data->name ?></h3>

                                    <p class="uk-text-muted">
                                        <span uk-icon="icon:clock"></span> &nbsp;<?= date('l g:i a') ?>
                                    </p>
                                    <p class="uk-text-muted">
                                        <span uk-icon="icon:calendar"></span> &nbsp;<?= date('jS F Y') ?>                          
                                    </p>
                                    <div>
                                        <p>
                                            <span class="uk-text-bold uk-text-large"><?= $data->weather[0]->description ?></span>
                                        </p>
                                        <img src="http://openweathermap.org/img/w/<?php echo $data->weather[0]->icon ?>.png" alt="">
                                        <span class="uk-text-large uk-text-blod uk-margin-right"><?= $data->main->temp_max ?>&deg;c </span>
                                        <span><?= $data->main->temp_min ?>&deg;c </span>
                                    </div>
                                    <p>
                                        <span class="uk-text-muted uk-text-bold">Humited: </span><span><?= $data->main->humidity ?>%</span><br>
                                        <span class="uk-text-muted uk-text-bold">Wind: </span><span><?= $data->wind->speed ?> km/h</span>
                                    </p>
                                </div>
                            </div>
                            
                        </div>

                    </div>
                </div>

                <script src="jquery-3.3.1.min.js"></script>
                <!-- Compiled and minified JavaScript -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
                <script>
                    $(document).ready(function(){
                        $('select').formSelect();
                    });
                </script>
                <!-- UIkit JS -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.7/js/uikit.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/3.1.7/js/uikit-icons.min.js"></script>
                
                <script>
                    $(document).ready(function(){
                        
                        $('#mybtn').attr("disabled", true);
                        $('#city_name').change(function(){
                        
                            // if($('option[disabled]:selected') ){
                            //     $('#mybtn').attr("disabled", false);
                            // }

                            if ($("#city_name option:selected")) {
                                $('#mybtn').attr("disabled", false);
                            }
                            
                        });

                    });
                </script>

            </body>
            </html>
        <?php
    }
?>

<?php /* require_once "session.php"; */ ?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>HI-REV</title>

    <?php /* require_once "_includes/rellink.php" */ ?>
  </head>
  <body>

    <!--
    <p class="col-12 mx-0 px-1">
      <a href="setup_product_list.php"><< Back</a>
    </p>
    -->

    <!--
    <div class="buffer"></div>
    -->

    <?php /* require_once "_includes/navbar_1001.php" */ ?>


    <div class="container-fluid px-0">
      <div class="row col-12 mx-0  px-0">
        <main role="main" class="col-12 py-3 px-0">

          <div class="row px-2 py-2  col-12 mx-0 justify-content-center">
            <h2>Import Voucher Master Data</h2>
          </div>

          <form id="inputform">
          <!--
          <h4 class="border-bottom px-3 section-title-h4">Workshop Detail Info</h4>
          -->
          <div class="form-row px-3 pt-3 mx-0">                        
            <div class="form-group col-md-10 offset-md-1 col-lg-6 offset-lg-3">

              <div class="form-row">
                <label for="excel_file" class="col-form-label col-md-4 required pl-2">Excel file: </label>
                <input type="file"  accept=".xls, .xlsx" id="excel_file" name="excel_file" class="form-control col-md-8"/>
                <span id="excel_file_err" class="error_message p-1 col-md-8 offset-md-4"></span>
              </div>

              <div class="form-row">
                <p id="output"></p>
              </div>

              <div class="form-row">
                <p id="output2"></p>
              </div>

            </div>            
          </div>

          
                   


          <div class="form-row px-3 pt-5 mt-5 mx-0">                        
            <div class="form-group col-md-12">
              <div class="form-row justify-content-center">
                <input type="button" id="preview_button" name="preview_button" value="Preview" onclick="preview()" class="form-control col-3 btn-primary col-md-2 mx-1"/>
                <input type="button" id="import_button" name="import_button" value="Import" onclick="import_data()" class="form-control col-3 btn-primary col-md-2 mx-1"/>
                <!--
                <input type="button" id="updateBtn" name="updateBtn" value="Delete" onclick="deleteRecord()" class="form-control col-3 btn-danger col-md-2 mx-1"/>
                -->
              </div>
            </div>
          </div>

        </form>

        </main>
      </div>
    </div>

    <?php /* require_once "_includes/footer_1001.php" */ ?>

    <?php  require_once "_includes/js.php"  ?>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous">    
    </script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
      $( document ).ready(function() {
        $("#import_button").hide();
        
        
      });


      

      




      function preview(){

        var form = $('#inputform')[0];

        // Create an FormData object 
        var data = new FormData(form);

        $.ajax({
          url: "_api/read.php",
          timeout:30000,
          type: "POST",
          enctype: 'multipart/form-data',
          processData: false,
          contentType: false,
          cache: false,
          data: data, 
          success: function(response){
            console.log(response.toString());
            $("#output").html(response.toString());
            $("#import_button").show();
            /*
            var data = JSON.parse(response);

            if(data.status.startsWith("success")){
              alert('Record saved successfully');
              

            } else if(data.status.startsWith("failed")){
              alert(data.msg);
            } else {
              alert(data.msg);
            }*/
          },
          error: function(jqXHR, textStatus){
            if(jqXHR.status ==404){
              alert(textStatus.toString());
            } else {
              alert(textStatus.toString());
            }            
          }
        });
      } // preview


      function import_data(){

        $("#import_button").hide();
        var form = $('#inputform')[0];

        // Create an FormData object 
        var data = new FormData(form);

        $.ajax({
          url: "_api/import.php",
          timeout:30000,
          type: "POST",
          enctype: 'multipart/form-data',
          processData: false,
          contentType: false,
          cache: false,
          data: data, 
          success: function(response){
            console.log(response.toString());
            $("#output").html(response.toString());
            /*
            var data = JSON.parse(response);

            if(data.status.startsWith("success")){
              alert('Record saved successfully');
              

            } else if(data.status.startsWith("failed")){
              alert(data.msg);
            } else {
              alert(data.msg);
            }*/
          },
          error: function(jqXHR, textStatus){
            if(jqXHR.status ==404){
              alert(textStatus.toString());
            } else {
              alert(textStatus.toString());
            }            
          }
        });
        } // import

      



    </script>

  </body>
</html>



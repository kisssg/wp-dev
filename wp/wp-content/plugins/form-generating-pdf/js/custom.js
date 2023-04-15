jQuery(function ($) {
  $(document).ready(function(){
    var __PDF_DOC,
        __CURRENT_PAGE,
        __TOTAL_PAGES,
        __PAGE_RENDERING_IN_PROGRESS = 0,
        __CANVAS = $('#pdf-canvas').get(0),
        __CANVAS2 = $('#pdf-canvas'),
        __CANVAS_CTX = (__CANVAS)?__CANVAS.getContext('2d') : '';

      function showPDF(pdf_url) {
        $("#pdf-loader").show();

        PDFJS.getDocument({ url: pdf_url }).then(function(pdf_doc) {
          __PDF_DOC = pdf_doc;
          __TOTAL_PAGES = __PDF_DOC.numPages;
          
          // Hide the pdf loader and show pdf container in HTML
          $("#pdf-loader").hide();
          $("#pdf-contents").show();
          $("#pdf-total-pages").text(__TOTAL_PAGES);

          // Show the first page
          showPage(1);
        }).catch(function(error) {
          // If error re-show the upload button
          $("#pdf-loader").hide();
          $("#upload-button").show();
          
          alert(error.message);
        });;
      }

      function showPage(page_no) {
        __PAGE_RENDERING_IN_PROGRESS = 1;
        __CURRENT_PAGE = page_no;

        // Disable Prev & Next buttons while page is being loaded
        $("#pdf-next, #pdf-prev").attr('disabled', 'disabled');

        // While page is being rendered hide the canvas and show a loading message
        $("#pdf-canvas").hide();
        $("#page-loader").show();
        $("#download-image").hide();

        // Update current page in HTML
        $("#pdf-current-page").text(page_no);
        
        // Fetch the page
        __PDF_DOC.getPage(page_no).then(function(page) {
          // As the canvas is of a fixed width we need to set the scale of the viewport accordingly
          var scale_required = __CANVAS.width / page.getViewport(1).width;
          
          // Get viewport of the page at required scale
          var viewport = page.getViewport(scale_required);

          // Set canvas height
          __CANVAS.height = viewport.height;

          var renderContext = {
            canvasContext: __CANVAS_CTX,
            viewport: viewport
          };
          
          // Render the page contents in the canvas
          page.render(renderContext).then(function() {
            __PAGE_RENDERING_IN_PROGRESS = 0;

            // Re-enable Prev & Next buttons
            $("#pdf-next, #pdf-prev").removeAttr('disabled');

            // Show the canvas and hide the page loader
            $("#pdf-canvas").show();
            $("#page-loader").hide();
            $("#download-image").show();
            $("#total-pages").val(__TOTAL_PAGES);
          });
        });
      }

      // Upon click this should should trigger click on the #file-to-upload file input element
      // This is better than showing the not-good-looking file input element
      $("#upload-button").on('click', function() {
        $("#file-to-upload").trigger('click');
      });

      // When user chooses a PDF file
      $("#file-to-upload").on('change', function() {
        // Validate whether PDF
          if(['application/pdf'].indexOf($("#file-to-upload").get(0).files[0].type) == -1) {
              alert('Error : Not a PDF');
              return;
          }

        $("#upload-button").hide();

        // Send the object url of the pdf
        showPDF(URL.createObjectURL($("#file-to-upload").get(0).files[0]));
      });

      // Previous page of the PDF
      $("#pdf-prev").on('click', function() {
        if(__CURRENT_PAGE != 1)
          showPage(--__CURRENT_PAGE);
      });

      // Next page of the PDF
      $("#pdf-next").on('click', function() {
        if(__CURRENT_PAGE != __TOTAL_PAGES)
          showPage(++__CURRENT_PAGE);
      });

      // Download button
      $("#download-image").on('click', function() {
        $(this).attr('href', __CANVAS.toDataURL()).attr('download', 'page.png');
      });



/*=============  Edit PDF Form Project ================*/
     $('.editProject').on('click', function(event){
      var idKey = $(this).attr('key');
      var data = {
          'action': 'fgpdf_edit_form_project',
          'form_project_id': idKey
        };
        jQuery.post(myAjaxLink.ajax_url, data);
        setTimeout(function() {
        $(location).attr('href', 'admin.php?page=fgpdf_options_admin_page');
        }, 1500);
        event.preventDefault();
        event.stopPropagation();

    });
     $('.deleteProject').on('click', function(event){
      var idKey = $(this).attr('key');
      var data = {
          'action': 'fgpdf_delete_form_project',
          'form_project_id': idKey
        };
        jQuery.post(myAjaxLink.ajax_url, data);
        $(this).parent().parent().remove();
        event.preventDefault();
        event.stopPropagation();
    });






    function getMousePos(canvas, evt) {
      var rect = __CANVAS.getBoundingClientRect();
      return {
        x: evt.clientX - rect.left,
        y: evt.clientY - rect.top
      };
    }
    var key = null;
    $(".positionElement").on('click', function(){
         key = $(this).attr('key');
         var pdfwidth;
         var pdfheight;
         __PDF_DOC.getPage(1).then( function(page) {

            //We need to pass it a scale for "getViewport" to work
            var scale = 1;

            //Grab the viewport with original scale
            var viewport = page.getViewport( 1 );

            //Here's the width and height
            pdfwidth = viewport.width;
            pdfheight = viewport.height;
            
        });
         
         __CANVAS2.mousemove(function(evt){
           var mousePos = getMousePos(__CANVAS2, evt);
           /*var canvaswidth = __CANVAS.width;
           var canvasheight = __CANVAS.height;
           var effectivePos = {
            x: (mousePos.x * pdfwidth)/canvaswidth,
            y: (mousePos.y * pdfheight)/canvasheight
          }*/
            $("#x" + key+"val").val(Math.floor(mousePos.x));
            $("#y" + key+"val").val(Math.floor(mousePos.y));
       });
    });
    $(".widthElement").on('click', function(){
         key = $(this).attr('key');
         var pdfwidth;
         var pdfheight;
         __PDF_DOC.getPage(1).then( function(page) {

            //We need to pass it a scale for "getViewport" to work
            var scale = 1;

            //Grab the viewport with original scale
            var viewport = page.getViewport( 1 );

            //Here's the width and height
            pdfwidth = viewport.width;
            pdfheight = viewport.height;
            
        });
         
         __CANVAS2.mousemove(function(evt){
           var mousePoswidth = getMousePos(__CANVAS2, evt).x;
           var horizontalVal = $("#x" + key+"val").val();
           var valueWidth = (horizontalVal < mousePoswidth) ? (mousePoswidth - horizontalVal) : 0;
            $("#width" + key+"val").val(Math.floor(valueWidth));
       });
    });


    $(".sizeElement").on('click', function(){
         key = $(this).attr('key');
         var pdfwidth;
         var pdfheight;
         __PDF_DOC.getPage(1).then( function(page) {

            //We need to pass it a scale for "getViewport" to work
            var scale = 1;

            //Grab the viewport with original scale
            var viewport = page.getViewport( 1 );

            //Here's the width and height
            pdfwidth = viewport.width;
            pdfheight = viewport.height;
            
        });
         
         __CANVAS2.mousemove(function(evt){
           var mousePoswidth = getMousePos(__CANVAS2, evt).x;
           var horizontalVal = $("#x" + key+"val").val();

           var mousePosHeight = getMousePos(__CANVAS2, evt).y;
           var verticalVal = $("#y" + key+"val").val();

           var valueWidth = (horizontalVal < mousePoswidth) ? (mousePoswidth - horizontalVal) : 0;
           var valueHeight = (verticalVal < mousePosHeight) ? (mousePosHeight - verticalVal) : 0;
            $("#larg" + key+"val").val(Math.floor(valueWidth));
            $("#haut" + key+"val").val(Math.floor(valueHeight));


       });
    });








    __CANVAS2.on("click", function() {
      __CANVAS2.off( "mousemove" );
      $("#page" + key+"val").val(__CURRENT_PAGE);
    });


    // Input radio-group visual controls
    $('.radio-group label').on('click', function(){
        $(this).removeClass('not-active').siblings().addClass('not-active');

    });
    $('#fgpdf_alternative_method').change(function() {
      $('#fgpdf_alternative_method_div').toggle();
    });


    $('#fgpdf_both').change(function() {
      if($('#fgpdf_both').is(":checked"))
        $('#fgpdf_admin_email_div').show();
    });

    $('#fgpdf_admin').change(function() {
      if($('#fgpdf_admin').is(":checked"))
        $('#fgpdf_admin_email_div').show();
    });

    $('#fgpdf_users').change(function() {
      if($('#fgpdf_users').is(":checked"))
        $('#fgpdf_admin_email_div').hide();
    });

var inputTypesForm = [];
    $('.formType').on('click', function(){
      var value = $(this).val();
      var inputObjectForm = {
        'type' : value,
        'inputValue' : []
      };

      inputTypesForm.push(inputObjectForm);
      $('#fgpdf_select_value').prop('disabled', true); 
      if(value !== 'select'){
        $newEl = $('<tr class="appendedElement"><td class="row-title">'+value+'</td><td><input type="text" class="inputNameForm" placeholder="Name" ></td><td><button type="button" id="deleteInput" class="button button-primary">Delete</button></td></tr>');
      }else{
      $newEl = $('<tr class="appendedElement"><td class="row-title">'+value+'</td><td class="cell"><div>Value: <input type="text" class="selectInputValue" value="myValue" ><button type="button" id="deleteValue" class="button button-primary">Delete Value</button></div></td><td> <button type="button" id="deleteInput" class="button button-primary">Delete</button> </td></tr>');
      $('#fgpdf_select_value').prop('disabled', false);
      }

      $(".tableAppendTo").append($newEl);
      
      $newEl.on("click", "button", function(){
      if($(this).attr('id') == 'deleteInput'){
        var removeItem = $(this).val();
        var indexDel = $(this).parent().parent().index('.appendedElement');
        $(this).parent().parent().remove();
        inputTypesForm.splice(indexDel,1);
      }else if($(this).attr('id') == 'deleteValue'){
        var idVariable = $(this).parent().parent().parent().index('.appendedElement');
        inputTypesForm[idVariable]['inputValue'].splice(0,1); 
        $(this).closest('div').remove();
      }

      });
      
    });

    $('#fgpdf_select_value').on("click", function(){
        $('#fgpdf_select_value').prop('disabled', false);
        $newEl = $('<div><br>Value: <input type="text" class="selectInputValue" value="myValue" > <button type="button" class="button button-primary" >Delete Value</button> </div>');
        $("td.cell:last").append($newEl);
        $newEl.on("click", "button", function(){
        var idVariable = $(this).parent().parent().parent().index('.appendedElement');
        var selectedVariable = $(this).parent().index();
        if(inputTypesForm[idVariable]['inputValue'].length != 0)
          inputTypesForm[idVariable]['inputValue'].splice(selectedVariable,1);
        $(this).parent().remove();
        
      });
      });


    $('.deleteItem').on('click', function(event){
      var idKey = $(this).attr('key');
      $(this).parent().parent().remove();
      var data = {
          'action': 'fgpdf_delete_element',
          'id': idKey
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(myAjaxLink.ajax_url, data);
        event.preventDefault();
        event.stopPropagation();

    });

    $('.deleteItemValue').on('click', function(event){
      var idKey = $(this).attr('key');
      var idx = $(this).attr('idx');
      $(this).parent().remove();
      var data = {
          'action': 'fgpdf_delete_value_item',
          'keyArray': [idKey, idx]
        };

        jQuery.post(myAjaxLink.ajax_url, data);
        event.preventDefault();
        event.stopPropagation();

    });


    $('#formValidate').on('click', function(event){
        i=0;
        while($('.appendedElement').eq(i).length != 0){
          if(inputTypesForm[i]['type'] == 'select'){
            inputTypesForm[i]['inputValue'].length = 0
            var j=0;
            while($('.appendedElement').eq(i).find('.selectInputValue').eq(j).val() != null ){
              inputTypesForm[i]['inputValue'].push($('.appendedElement').eq(i).find('.selectInputValue').eq(j).val());
              j++;
            }
            i++;
          }else{
            inputTypesForm[i]['inputValue'][0] = $('.appendedElement').eq(i).find('.inputNameForm').val();
            i++;
          }
      }
        var data = {
          'action': 'fgpdf_my_form_types',
          'inputElement': inputTypesForm
        };

        // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(myAjaxLink.ajax_url, data);
        $(this).prop('disabled', true); 
        event.preventDefault();
        event.stopPropagation();
      });

   });
});
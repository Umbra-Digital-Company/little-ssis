<p>SSIS v2.6.2</p> <br>

<p>Please click the buttons one at a time and allow the system to download the data. Once the page refreshes, you may click another button.</p>
<div class="mt-3">
    <div class="d-flex align-items-center">
        <select name="updatedb" id="updatedb" class="form-control select filled">
            <option value="none" selected="selected">Select Data To Update</option>
            <option value="poll_51">Poll 51</option>
            <option value="employee">Employee</option>
            <option value="store_data">Store Data</option>
        </select>
        <button type="button" class="btn btn-primary ml-3 btn-updatedb" disabled="disabled">update</button>
    </div>
</div>

<!-- <div class="btn btn-primary btn-md btn-poll-51 btn-danger">Update POLL 51</div>
<div class="btn btn-primary btn-md btn-employee btn-danger">Update Employee</div>
<div class="btn btn-primary btn-md btn-store btn-danger">Update store data</div> -->

<div class="stat mt-3"></div>

<script>

 	$(document).ready(function(){

        setTimeout(function() { location.reload() },3600000 );

        var dataResult = "";

        const refreshPage = () => {
        	if(dataResult == "success") {
               location.reload();
            }
        }

        const sendAjax = x => {
            if (x == 'update-poll51'){
                 $('.stat').load('modules/process/update_studios/update_poll51_studios.php');
                 location.reload();
            } else if (x == 'employee'){
                 $('.stat').load('modules/process/update_studios/update_employee_studios.php');
                 location.reload();
            } else if (x == 'store'){
                 $('.stat').load('modules/process/update_studios/update_store_studios.php');
                 location.reload();
            }
           $('.ssis-loading').fadeIn();
        }

        let $value      = $('#updatedb');
        let $updatedb   = $('.btn-updatedb');
        let $update     = "";

        $value.on('change', function() {
            $update = (this.value);
            ( $update == 'none' ) ? $updatedb.prop('disabled', true) : $updatedb.prop('disabled', false);
        });

        $updatedb.on('click', function() {
            switch ( $update ) {
                case 'poll_51' : 
                    sendAjax('update-poll51');
                    break;

                case 'employee' :
                    sendAjax('employee');
                    break;

                case 'store_data' :
                    sendAjax('store');
                    break;

                default :
                    console.log('select data to download');
                    break;
            }
        });

    });	

</script>
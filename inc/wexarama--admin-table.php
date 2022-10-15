<?php
function dv_live_settings()
{ ?>

    <div class="container wrap">
        <h4>تنظیمات سیستم</h4>
        <div>
            <div class="button" id='get_update_counter'> Update Count Image's </div>
            <div class="button" id='get_update_thumbnail'> Update Thumbnail </div>
        </div>
        <br>
        <div class="progress-bar">
            <span class="progress-bar-fill" style="width: 0%;"></span>
        </div>
        <br>
        <table class="wp-list-table widefat fixed striped table-view-list posts table-update-counter">
            <thead>
                <tr>
                    <th scope="col">ردیف</th>
                    <th scope="col">شماره پست</th>
                    <th scope="col">نام پست</th>
                    <th scope="col">تعداد</th>
                </tr>
            </thead>
            <tbody id='the-list'></tbody>
        </table>
    </div>


<?php }

<script>
    if (inIframe()) {
        top.location.href = location.href;
    }

    function inIframe() {
        try {
            return window.self !== window.top;
        } catch (e) {
            return true;
        }
    }
</script>

<?php
    if($_GET['s1'] != 'login') {
?>
<style>
    #synergyscape_header {
        background-color:white;
        border-bottom:1px solid #eee;
        text-align:center;
    }
    #header_content {
        width:1080px;
        position:relative;
        text-align:left;
    }
    #header_items {
        display:table;
        height:100px;
    }
    .header_item {
        padding:0px;
        margin:0px;
        display:table-cell;
        vertical-align:middle;
        text-align:center;
    }
    .menu_item {
        text-align:center;
        font-family: "proxima-nova",Helvetica,Arial,sans-serif;
        color:#919999;
        padding:0px 15px;
        cursor:pointer;
    }
    .menu_item.active {
        text-align:center;
        background-color:#16a6e3;
        color:white;
    }
</style>
<div class="container-fluid" id="synergyscape_header">
    <div class="container">
        <center>
            <div id="header_content">
                <div id="header_items">
                	<div class="header_item" style="width:235px;border-right:1px solid #eee;">
                        <center>
                		  <img src="images/logo_ss.png">
                        </center>
                	</div>
                    <div style="width:80px">
                    </div>
                    <div class="header_item menu_item active" onClick="location.href='<?= SS_SECURE_URL ?>pages/arportal'">
                        AR Portal
                    </div>
                    <div class="header_item menu_item" onClick="location.href='<?= SS_SECURE_URL ?>members'">
                        Members
                    </div>
                    <div class="header_item menu_item" onClick="location.href='<?= SS_SECURE_URL ?>groups'">
                        Groups
                    </div>
                    <div class="header_item menu_item" onClick="location.href='<?= SS_SECURE_URL ?>stories'">
                        Stories
                    </div>
                    <div class="header_item menu_item" onClick="location.href='<?= SS_SECURE_URL ?>courses'">
                        Training
                    </div>
                    <div class="header_item menu_item" onClick="location.href='<?= SS_SECURE_URL ?>opportunities'">
                        Opportunities
                    </div>
                    <div style="width:80px">
                    </div>
                    <div class="header_item" style="padding:0px 20px;border-left:1px solid #eee;">
                        <iframe src="https://artrainer.synergyscape.com/loginframe" width="60" height="50" scrolling="no" class="ss-frame" frameborder="0"></iframe>
                    </div>
                </div>
            </div>
        </center>
    </div>
</div>

<?php
    }
?>
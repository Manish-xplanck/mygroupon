<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename constant.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 





define('DBCMax', true);


define('USR_ANY', 0);


define('ORD_ID_ANY', 0);

define('PRO_ACV_Yes', true);

define('PRO_ACV_No', false);

define('PRO_DSP_None', 0);

define('PRO_DSP_City', 1);

define('PRO_DSP_Global', 2);

define('PRO_STA_Failed', 0);

define('PRO_STA_Normal', 1);

define('PRO_STA_Success', 2);

define('PRO_STA_Finish', 3);

define('PRO_STA_Refund', 4);


define('ORD_PAID_ANY', -1);

define('ORD_PAID_Yes', 1);

define('ORD_PAID_No', 0);

define('ORD_STA_ANY', -1);

define('ORD_STA_Cancel', 0);

define('ORD_STA_Normal', 1);

define('ORD_STA_Overdue', 2);

define('ORD_STA_Failed', 3);

define('ORD_STA_Refund', 4);


define('TICK_STA_ANY', -1);

define('TICK_STA_Unused', 0);

define('TICK_STA_Used', 1);

define('TICK_STA_Overdue', 2);

define('TICK_STA_Invalid', 3);


define('IMG_Tiny', -1);

define('IMG_Small', -2);

define('IMG_Normal', -3);

define('IMG_Original', -4);


define('INI_DELETE', microtime());

define('DELIV_SEND_ANY', 0);

define('DELIV_SEND_Yes', 1);

define('DELIV_SEND_No', 2);


define('EXPORT_GENEALL_FLAG', 'exportGENERATEall');

define('EXPORT_GENEALL_VALUE', 'EGAYes');


define('UI_LOADER_ONCE', true);

?>
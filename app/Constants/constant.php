<?php
/**
 * Created by PhpStorm.
 * User: Ominext
 * Date: 3/21/2018
 * Time: 3:25 PM
 */
// time off status
define('TYPE_IN_LATE', 1);
define('TYPE_LEAVE_EARLY', 2);
define('TYPE_LEAVE_OUT', 3);
define('TYPE_DID_NOT_CHECK_OUT_CHECK_IN', 4);
define('TYPE_ALL_DAY', 5);
define('TYPE_MULTIPLE_DAYS', 6);
define('TYPE_OT', 7);
//
define('STORE_STATUS', 1);
define('UPDATE_STATUS', 2);
define('DELETE_STATUS', 3);

define('EMPLOYEE_UPDATE_APPROVE_PENDING', 0);
define('EMPLOYEE_UPDATE_APPROVE_APPROVED', 1);
define('EMPLOYEE_UPDATE_APPROVE_REJECTED', 2);

define('DEFAULT_PAGE_LIMIT', 15);
define('DEFAULT_DISPLAY_PAGE', 1);

define('DEFAULT_PAGINATION_LIMIT', 15);
define('DEFAULT_PAGINATION_PAGE', 1);

define('DEFAULT_AVATAR_WIDTH', 256);
define('DEFAULT_AVATAR_HEIGHT', 256);
define('DEFAULT_IMAGE_WIDTH', 512);
define('DEFAULT_IMAGE_HEIGHT', 256);
define('RESIZE_IMAGE_FORMAT', 'png');
define('PREFIX_IMAGE_NAME', 'img_');
define('MAX_IMAGE_SIZE', 5000);  //kb

define('EMPLOYEE_AVATAR_DIR', 'images/employee_avatar/');
define('EMPLOYEE_ATTACH_FILES_DIR', 'employee_attach_file/');
define('EMPLOYEE_EXCEL_FILES_DIR', 'personnel_excel/');
define('TIME_ON_EXCEL_FILES_DIR', 'time_on_excel/');
// time off approved status
define('PENDING', 0);
define('APPROVED', 1);
define('REFUSED', 2);
define('EXPIRE_MASSAGE', 'Hết hạn duyệt');
define('EXPIRE_REQUEST_DAY', 3);
define('EXPIRE_APPROVE_DAY', 3);

// Employees
define('EMPLOYEE_GENDER_UNDEFINED', 0);
define('EMPLOYEE_GENDER_MALE', 1);
define('EMPLOYEE_GENDER_FEMALE', 2);
//
define('FCM_SERVER_KEY', 'AAAAp99sBr0:APA91bGupBttmhGkZgr7v6AaZIwgleLFKgd3taPo67tuss1D8Sbo0RNZNii816r26K9mtiLHH5vzk9YXYZ7kZ2sBQ7nM5mkmAeyUDSaKzAcn9rcJE50KKOtlIGQ1UpvIckt3oAF3ML8f');
define('FCM_URL', 'https://fcm.googleapis.com/fcm/send');

define('EMPLOYEE_EXCEL_FILE_STATUS_DELETED', 0);
define('EMPLOYEE_EXCEL_FILE_STATUS_IMPORTED', 1);
define('EMPLOYEE_EXCEL_FILE_STATUS_UPLOADED', 2);
define('EMPLOYEE_EXCEL_FILE_STATUS_PENDING', 3);
define('EMPLOYEE_EXCEL_FILE_STATUS_IMPORTING', 4);
define('EMPLOYEE_EXCEL_FILE_STATUS_FAILED', 5);
define('EMPLOYEE_EXCEL_FILE_STATUS_REJECTED', 6);

define('TIME_ON_STATUS_HAS_DATA', 1);
define('TIME_ON_STATUS_MISSING_DATA', 0);

define('TIME_ON_IMPORTED', 1);
define('TIME_ON_UNIMPORTED', 0);

// news scope
define('SCOPE_PUBLIC', 1);
define('SCOPE_PRIVATE', 2);

define('TIME_ON_EXCEL_FILE_STATUS_DELETED', 0);
define('TIME_ON_EXCEL_FILE_STATUS_IMPORTED', 1);
define('TIME_ON_EXCEL_FILE_STATUS_UPLOADED', 2);
define('TIME_ON_EXCEL_FILE_STATUS_PENDING', 3);
define('TIME_ON_EXCEL_FILE_STATUS_IMPORTING', 4);
define('TIME_ON_EXCEL_FILE_STATUS_FAILED', 5);
define('TIME_ON_EXCEL_FILE_STATUS_REJECTED', 6);

define('ERROR_MESSAGE_SEND_EMAIL_VALIDATION', 'Thiếu dữ liệu nhân viên');
define('ERROR_MESSAGE_SEND_EMAIL', 'Không gửi mail');

define('TIME_OFF_STATUS_TYPE_123', 'Đi muộn/Về sớm/Xin ra ngoài - In Late/Leave Early/Leave Out');
define('TIME_OFF_STATUS_TYPE_4', 'Không checkin/checkout / Did not checkin/checkout');
define('TIME_OFF_STATUS_TYPE_5', 'Nghỉ 1 ngày / Allday');
define('TIME_OFF_STATUS_TYPE_6', 'Nghỉ nhiều ngày / Multiple days');
//
define('DEVICES_TYPE_ANDROID', 0);
define('DEVICES_TYPE_IOS', 1);
define('DEVICES_TYPE_WEB', 2);
//
define('HRM_ICON', 'https://sv1.uphinhnhanh.com/images/2018/06/18/s.png');
define('ACTION_HOME', 'trang-chu');
//
define('NOTIFICATION_TYPE_DEFAULT', 0);
define('NOTIFICATION_TYPE_TIME_OFF', 1);
define('NOTIFICATION_TYPE_OVER_TIME', 2);
define('NOTIFICATION_TYPE_COURSE', 3);
define('NOTIFICATION_TYPE_TRAINING', 4);
define('NOTIFICATION_TYPE_TIME_OFF_APPROVED', 5);
define('NOTIFICATION_TYPE_TIME_OFF_REFUSED', 6);
define('NOTIFICATION_TYPE_OVER_TIME_APPROVED', 7);
define('NOTIFICATION_TYPE_OVER_TIME_REFUSED', 8);
//
define('SPECIFIC_ROLE_TEAM_LEADER','team leader');
define('SPECIFIC_ROLE_PROJECT_MANAGER','project manager');
define('SPECIFIC_ROLE_BOM','bom');

define('JOS_STATUS_CODE_OFFICIAL','official');
define('JOS_STATUS_CODE_TRAINING','training');
define('JOS_STATUS_CODE_COLLABORATOR','collaborator');
define('WORKING_STATUS_CODE_WORKING','working');
define('WORKING_STATUS_CODE_CHECKOUT','checkout');

define('TIME_ON_ACCUMULATED_YEAR_WORKING', 1);
define('TIME_ON_ACCUMULATED_YEAR_CHECKOUT', 0);

define('TIME_ON_ADDITION_DAY_OFF_TYPE_MONTHLY', 1);
define('TIME_ON_ADDITION_DAY_OFF_TYPE_MANUAL', 2);
define('TIME_ON_ADDITION_DAY_OFF_TYPE_TIME_ON', 3);
define('TIME_ON_ADDITION_DAY_OFF_TYPE_OT', 4);

define('TIME_ON_ADDITION_DAY_OFF_STATUS_COUNT', 1);
define('TIME_ON_ADDITION_DAY_OFF_STATUS_NOT_COUNT', 2);
define('TIME_ON_ADDITION_DAY_OFF_STATUS_CHECKOUT', 3);
define('TIME_ON_ADDITION_DAY_OFF_STATUS_DELETED', 0);

define('TIME_ON_ADDITION_DAY_OFF_MANUAL_TYPE_PERMIT', 1);
define('TIME_ON_ADDITION_DAY_OFF_MANUAL_TYPE_OT', 2);

define('DEFAULT_DAY_OFF_RESET_TIME', '30-06');
define('ADMIN_EMAIL','admin@ominext.com');
define('DEFAULT_PASSWORD', 'Ominext123');

define('OT_REASON','Nghỉ vì làm ngoài giờ ngày hôm trước');

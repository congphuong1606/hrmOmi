export const DEVICE_ID = 'device_id';
export const REMEMBER_LOGIN = 'remember_login';
export const URLS_NAME = 'jwt_urls';
export const USER_INFO = 'jwt_user_info';
export const TIME_CONFIG = 'time_config';
export const LIST_REASONS = [
    'Lý do khác',
    'Ốm (Ốm hoặc bị thương) / Sick leave ',
    'Họ hàng, người thân mất / Bereavement ',
    'Lý do cá nhân / Personal leave',
    'Làm thủ tục hành chính/ Jury duty or legal ',
    'Nghỉ đột xuất / Emergency leave',
    'Nghỉ tạm thời / Temporary leave',
    'Nghỉ không lương / Leave without pay',
    'Nghỉ vì làm ngoài giờ ngày hôm trước'

];
export const LIST_URL_ROUTER: string[] = [
    '/quan-tri',
    '/quan-tri/quan-ly-phan-quyen',
    '/quan-tri/them-vai-tro',
    '/quan-tri/thiet-lap-quyen-trong-vai-tro',
    '/quan-tri/cap-nhat-vai-tro',
    '/quan-tri/them-nguoi-dung-trong-vai-tro',
    '/quan-tri/xem-nguoi-dung-trong-vai-tro',

    '/danh-sach-nhan-su',
    '/danh-sach-nhan-su/danh-sach',
    '/danh-sach-nhan-su/nguon-luc',
    '/danh-sach-nhan-su/thong-tin-nhan-su',
    '/danh-sach-nhan-su/them-nhan-su',
    '/danh-sach-nhan-su/quan-ly-thay-doi',
    '/danh-sach-nhan-su/import',
    '/danh-sach-nhan-su/sua-thong-tin-nhan-su',
    '/danh-sach-nhan-su/ho-so',
    '/danh-sach-nhan-su/sua-ho-so',
    '/danh-sach-nhan-su/doi-mat-khau',

    '/lam-them-gio-va-nghi-phep',
    '/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-vang-mat',
    '/lam-them-gio-va-nghi-phep/di-muon-ve-som',
    '/lam-them-gio-va-nghi-phep/nghi-theo-ngay',
    '/lam-them-gio-va-nghi-phep/duyet-nghi-phep',
    // '/lam-them-gio-va-nghi-phep/duyet-lam-them-gio',
    '/lam-them-gio-va-nghi-phep/lam-them-gio',
    '/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-lam-them-gio',
    '/lam-them-gio-va-nghi-phep/thong-ke',

    '/dao-tao',
    '/dao-tao/danh-sach-khoa-hoc',
    '/dao-tao/quan-ly-khoa-hoc',
    '/dao-tao/tao-khoa-hoc',
    '/dao-tao/cap-nhat-khoa-hoc',
    '/dao-tao/tuy-chinh-doi-tuong-dao-tao-cho-khoa-hoc',
    '/dao-tao/quan-ly-nhan-vien-trong-khoa-hoc',

    '/cham-cong',
    '/cham-cong/duyet-du-lieu',
    '/cham-cong/check-in-check-out',
    '/cham-cong/bang-tong',
    '/cham-cong/bang-ca-nhan',
    '/cham-cong/tich-luy-tong',

    '/danh-muc',
    '/danh-muc/chuc-danh',
    '/danh-muc/them-chuc-danh',
    '/danh-muc/cap-nhat-chuc-danh',
    '/danh-muc/trang-thai-cong-viec',
    '/danh-muc/them-trang-thai-cong-viec',
    '/danh-muc/cap-nhat-trang-thai-cong-viec',
    '/danh-muc/man-hinh',
    '/danh-muc/cap-nhat-danh-muc-man-hinh',
    '/danh-muc/them-danh-muc-man-hinh',
    '/danh-muc/phong-ban',
    '/danh-muc/them-phong-ban',
    '/danh-muc/cap-nhat-phong-ban',
    '/danh-muc/cac-ngay-nghi-le',
    '/danh-muc/them-cac-ngay-nghi-le',
    '/danh-muc/cap-nhat-cac-ngay-nghi-le',
    '/danh-muc/danh-muc-khac',
    '/danh-muc/danh-sach-trong-danh-muc-khac',
    '/danh-muc/cap-nhat-danh-muc-khac',
    '/danh-muc/them-danh-muc-khac',
    '/danh-muc/danh-sach-trong-danh-muc-khac/ky-nang-chuyen-mon'
];


export const LIST_OTHER_CATEGORY: any[] = [
    {
        name: 'Kỹ năng chuyên môn',
        path: 'ky-nang-chuyen-mon',
        type: 'specialized_skills',
    },
    {
        name: 'Tình trạng làm việc',
        path: 'tinh-trang-lam-viec',
        type: 'working_status',
    },
    {
        name: 'Các khóa học',
        path: 'cac-khoa-hoc',
        type: 'categoryCourses',
    },
    {
        name: 'Các phòng',
        path: 'cac-phong',
        type: 'room',
    },
    {
        name: 'Các dự án',
        path: 'cac-du-an',
        type: 'projects',
    },
    {
        name: 'Các lý do cho phép đi muộn',
        path: 'cac-ly-do-cho-phep-di-muon',
        type: 'reasons',
    },
    {
        name: 'Các ngày nghỉ lễ',
        path: 'cac-ngay-nghi-le',
        type: '1',
    },

    {
        name: 'Tỉnh Thành',
        path: 'tinh-thanh',
        type: '2',
    },

    {
        name: 'Giới Tính',
        path: 'gioi-tinh',
        type: '3',
    },
    {
        name: 'Loại hợp đồng',
        path: 'loai-hop-dong',
        type: '4',
    },
    {
        name: 'Loại báo cáo',
        path: 'loai-bao-cao',
        type: '5',
    },
    {
        name: 'bảo hiểm',
        path: 'bao-hiem',
        type: '6',
    },
    {
        name: 'Loại nhân viên',
        path: 'loai-nhan-vien',
        type: '7',
    },
    {
        name: 'Trình độ học vấn',
        path: 'trinh-do-hoc-van',
        type: '8',
    },
    {
        name: 'Loại quyết định',
        path: 'loai-quyet-dinh',
        type: '9',
    },
    {
        name: 'Tình trạng đóng bảo hiểm xã hội',
        path: 'tinh-trang-dong-bao-hiem-xa-hoi',
        type: '10',
    },
    {
        name: 'Các khoản giảm trừ',
        path: 'cac-khoan-giam-tru',
        type: '11',
    },
    {
        name: 'Các khoản phụ cấp',
        path: 'cac-khoan-phu-cap',
        type: '12',
    },
    {
        name: 'Kỳ lương',
        path: 'ky-luong',
        type: '13',
    },
    {
        name: 'Giảm trừ theo lương',
        path: 'giam-tru-theo-luong',
        type: '14',
    },
    {
        name: 'Phụ cấp theo lương',
        path: 'phu-cap-theo-luong',
        type: '15',
    },
    {
        name: 'Ngày lễ tết được nghỉ',
        path: 'ngay-le-tet-duoc-nghi',
        type: '16',
    }
    ,
    {
        name: 'Vị trí công việc',
        path: 'vi-tri-cong-viec',
        type: 'job_positions',
    }

]




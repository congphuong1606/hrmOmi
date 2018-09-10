<?php

use App\Models\Screen;
use App\Models\ScreenCategory;
use Illuminate\Database\Seeder;

class ScreensTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        try {
            DB::beginTransaction();
            $manage = new ScreenCategory();
            $manage->name = 'Quản trị';
            $manage->description = 'Quản trị';
            $manage->display_name = 'Quản trị';
            $manage->save();

            $personnel = new ScreenCategory();
            $personnel->name = 'Danh sách nhân sự';
            $personnel->description = 'Danh sách nhân sự';
            $personnel->display_name = 'Danh sách nhân sự';
            $personnel->save();

            $timeOn = new ScreenCategory();
            $timeOn->name = 'Chấm công';
            $timeOn->description = 'Chấm công';
            $timeOn->display_name = 'Chấm công';
            $timeOn->save();

            $category = new ScreenCategory();
            $category->name = 'Danh mục';
            $category->description = 'Danh mục';
            $category->display_name = 'Danh mục';
            $category->save();

            $training = new ScreenCategory();
            $training->name = 'Đào tạo';
            $training->description = 'Đào tạo';
            $training->display_name = 'Đào tạo';
            $training->save();

            $timeOff = new ScreenCategory();
            $timeOff->name = 'OT/Nghỉ phép';
            $timeOff->description = 'OT/Nghỉ phép';
            $timeOff->display_name = 'OT/Nghỉ phép';
            $timeOff->save();
            $now = \Carbon\Carbon::now()->toDateTimeString();
            $screens = [
                [
                    'name' => 'Quản trị => Quản lý phân quyền',
                    'display_name' => 'Quản trị => Quản lý phân quyền',
                    'description' => 'Quản trị',
                    'url' => '/quan-tri/quan-ly-phan-quyen',
                    'screen_category_id' => $manage->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Quản trị => Thêm vai trò',
                    'display_name' => 'Quản trị => Thêm vai trò',
                    'description' => 'Quản trị',
                    'url' => '/quan-tri/them-vai-tro',
                    'screen_category_id' => $manage->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Quản trị => Thiết lập quyền trong vai trò',
                    'display_name' => 'Quản trị => Thiết lập quyền trong vai trò',
                    'description' => 'Quản trị',
                    'url' => '/quan-tri/thiet-lap-quyen-trong-vai-tro',
                    'screen_category_id' => $manage->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Quản trị => Cập nhật vai trò',
                    'display_name' => 'Quản trị => Cập nhật vai trò',
                    'description' => 'Quản trị',
                    'url' => '/quan-tri/cap-nhat-vai-tro',
                    'screen_category_id' => $manage->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Quản trị => Xem người dùng trong vai trò',
                    'display_name' => 'Quản trị => Xem người dùng trong vai trò',
                    'description' => 'Quản trị',
                    'url' => '/quan-tri/xem-nguoi-dung-trong-vai-tro',
                    'screen_category_id' => $manage->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Quản trị => Thêm người dùng cho vai trò',
                    'display_name' => 'Quản trị => Thêm người dùng cho vai trò',
                    'description' => 'Quản trị',
                    'url' => '/quan-tri/them-nguoi-dung-trong-vai-tro',
                    'screen_category_id' => $manage->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Nhân sự => Danh sách',
                    'display_name' => 'Nhân sự => Danh sách',
                    'description' => 'Danh sách nhân sự',
                    'url' => '/danh-sach-nhan-su/danh-sach',
                    'screen_category_id' => $personnel->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Nhân sự => Thông tin nhân sự',
                    'display_name' => 'Nhân sự => Thông tin nhân sự',
                    'description' => 'Danh sách nhân sự',
                    'url' => '/danh-sach-nhan-su/thong-tin-nhan-su',
                    'screen_category_id' => $personnel->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Nhân sự  => Thêm nhân sự',
                    'display_name' => 'Nhân sự  => Thêm nhân sự',
                    'description' => 'Danh sách nhân sự',
                    'url' => '/danh-sach-nhan-su/them-nhan-su',
                    'screen_category_id' => $personnel->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Nhân sự  => Quản lý thay đổi',
                    'display_name' => 'Nhân sự  => Quản lý thay đổi',
                    'description' => 'Danh sách nhân sự',
                    'url' => '/danh-sach-nhan-su/quan-ly-thay-doi',
                    'screen_category_id' => $personnel->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Nhân sự  => Import',
                    'display_name' => 'Nhân sự  => Import',
                    'description' => 'Danh sách nhân sự',
                    'url' => '/danh-sach-nhan-su/import',
                    'screen_category_id' => $personnel->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Nhân sự => Sửa thông tin nhân sự',
                    'display_name' => 'Nhân sự => Sửa thông tin nhân sự',
                    'description' => 'Danh sách nhân sự',
                    'url' => '/danh-sach-nhan-su/sua-thong-tin-nhan-su',
                    'screen_category_id' => $personnel->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Nhân sự => Hồ sơ của tôi',
                    'display_name' => 'Nhân sự => Hồ sơ của tôi',
                    'description' => 'Danh sách nhân sự',
                    'url' => '/danh-sach-nhan-su/ho-so',
                    'screen_category_id' => $personnel->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Nhân sự => Sửa hồ sơ của tôi',
                    'display_name' => 'Nhân sự => Sửa hồ sơ của tôi',
                    'description' => 'Danh sách nhân sự',
                    'url' => '/danh-sach-nhan-su/sua-ho-so',
                    'screen_category_id' => $personnel->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh sách nhân sự => Đổi mật khẩu',
                    'display_name' => 'Danh sách nhân sự => Đổi mật khẩu',
                    'description' => 'Danh sách nhân sự',
                    'url' => '/danh-sach-nhan-su/doi-mat-khau',
                    'screen_category_id' => $personnel->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Nguồn lực',
                    'display_name' => 'Nguồn lực',
                    'description' => 'Danh sách nhân sự',
                    'url' => '/danh-sach-nhan-su/nguon-luc',
                    'screen_category_id' => $personnel->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Chấm công => Duyệt dữ liệu',
                    'display_name' => 'Chấm công => Duyệt dữ liệu',
                    'description' => 'Chấm công',
                    'url' => '/cham-cong/duyet-du-lieu',
                    'screen_category_id' => $timeOn->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Chấm công => Bảng tổng',
                    'display_name' => 'Chấm công => Bảng tổng',
                    'description' => 'Chấm công',
                    'url' => '/cham-cong/bang-tong',
                    'screen_category_id' => $timeOn->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Chấm công => Dữ liệu chấm công',
                    'display_name' => 'Chấm công => Dữ liệu chấm công',
                    'description' => 'Chấm công',
                    'url' => '/cham-cong/check-in-check-out',
                    'screen_category_id' => $timeOn->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Chấm Công => Bảng chấm công của tôi',
                    'display_name' => 'Chấm Công => Bảng chấm công của tôi',
                    'description' => 'Chấm công',
                    'url' => '/cham-cong/bang-ca-nhan',
                    'screen_category_id' => $timeOn->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Chức danh',
                    'display_name' => 'Danh mục => Chức danh',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/chuc-danh',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Thêm chức danh',
                    'display_name' => 'Danh mục => Thêm chức danh',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/them-chuc-danh',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Cập nhật chức danh',
                    'display_name' => 'Danh mục => Cập nhật chức danh',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/cap-nhat-chuc-danh',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => danh mục khác',
                    'display_name' => 'Danh mục => danh mục khác',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/danh-muc-khac',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => danh sách danh mục khác',
                    'display_name' => 'Danh mục => danh sách danh mục khác',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/danh-sach-trong-danh-muc-khac',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => thêm danh mục khác',
                    'display_name' => 'Danh mục => thêm danh mục khác',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/them-danh-muc-khac',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Cập nhật danh mục khác',
                    'display_name' => 'Danh mục => Cập nhật danh mục khác',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/cap-nhat-danh-muc-khac',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Màn hình',
                    'display_name' => 'Danh mục => Màn hình',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/man-hinh',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Thêm màn hình',
                    'display_name' => 'Danh mục => Thêm màn hình',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/them-danh-muc-man-hinh',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Cập nhật màn hình',
                    'display_name' => 'Danh mục => Cập nhật màn hình',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/cap-nhat-danh-muc-man-hinh',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Phòng ban',
                    'display_name' => 'Danh mục => Phòng ban',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/phong-ban',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Thêm phòng ban',
                    'display_name' => 'Danh mục => Thêm phòng ban',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/them-phong-ban',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục =>Cập nhật phòng ban',
                    'display_name' => 'Danh mục =>Cập nhật phòng ban',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/cap-nhat-phong-ban',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Trạng thái công việc',
                    'display_name' => 'Danh mục => Trạng thái công việc',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/trang-thai-cong-viec',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Thêm trạng thái công việc',
                    'display_name' => 'Danh mục => Thêm trạng thái công việc',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/them-trang-thai-cong-viec',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Cập nhật trạng thái công việc',
                    'display_name' => 'Danh mục => Cập nhật trạng thái công việc',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/cap-nhat-trang-thai-cong-viec',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Các ngày nghỉ lễ',
                    'display_name' => 'Danh mục => Các ngày nghỉ lễ',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/cac-ngay-nghi-le',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Cập nhật các ngày nghỉ lễ',
                    'display_name' => 'Danh mục => Cập nhật các ngày nghỉ lễ',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/cap-nhat-cac-ngay-nghi-le',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Danh mục => Thêm các ngày nghỉ lễ',
                    'display_name' => 'Danh mục => Thêm các ngày nghỉ lễ',
                    'description' => 'Danh mục',
                    'url' => '/danh-muc/them-cac-ngay-nghi-le',
                    'screen_category_id' => $category->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Đào tạo => Danh sách khóa học',
                    'display_name' => 'Đào tạo => Danh sách khóa học',
                    'description' => 'Đào tạo',
                    'url' => '/dao-tao/danh-sach-khoa-hoc',
                    'screen_category_id' => $training->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Đào tạo => Quản lý khóa học',
                    'display_name' => 'Đào tạo => Quản lý khóa học',
                    'description' => 'Đào tạo',
                    'url' => '/dao-tao/quan-ly-khoa-hoc',
                    'screen_category_id' => $training->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Đào tạo => Tạo khóa học',
                    'display_name' => 'Đào tạo => Tạo khóa học',
                    'description' => 'Đào tạo',
                    'url' => '/dao-tao/tao-khoa-hoc',
                    'screen_category_id' => $training->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Đào tạo => Cập nhật khóa học',
                    'display_name' => 'Đào tạo => Cập nhật khóa học',
                    'description' => 'Đào tạo',
                    'url' => '/dao-tao/cap-nhat-khoa-hoc',
                    'screen_category_id' => $training->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Đào tạo => Tùy chỉh đối tượng đào tạo cho khóa học',
                    'display_name' => 'Đào tạo => Tùy chỉh đối tượng đào tạo cho khóa học',
                    'description' => 'Đào tạo',
                    'url' => '/dao-tao/tuy-chinh-doi-tuong-dao-tao-cho-khoa-hoc',
                    'screen_category_id' => $training->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'Đào tạo => Quản lý nhân viên trong khóa học',
                    'display_name' => 'Đào tạo => Quản lý nhân viên trong khóa học',
                    'description' => 'Đào tạo',
                    'url' => '/dao-tao/quan-ly-nhan-vien-trong-khoa-hoc',
                    'screen_category_id' => $training->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'OT/Nghỉ phép => Tạo/ Chỉnh  sửa đơn văng mặt',
                    'display_name' => 'OT/Nghỉ phép => Tạo/ Chỉnh  sửa đơn văng mặt',
                    'description' => 'Nghỉ phép',
                    'url' => '/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-vang-mat',
                    'screen_category_id' => $timeOff->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'OT/Nghỉ phép => Danh sách nghỉ theo ngày',
                    'display_name' => 'OT/Nghỉ phép => Danh sách nghỉ theo ngày',
                    'description' => 'Nghỉ phép',
                    'url' => '/lam-them-gio-va-nghi-phep/nghi-theo-ngay',
                    'screen_category_id' => $timeOff->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'OT/Nghỉ phép => Duyệt nghỉ phép',
                    'display_name' => 'OT/Nghỉ phép => Duyệt nghỉ phép',
                    'description' => 'Nghỉ phép',
                    'url' => '/lam-them-gio-va-nghi-phep/duyet-nghi-phep',
                    'screen_category_id' => $timeOff->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'OT/Nghỉ phép => Danh sách làm thêm giờ',
                    'display_name' => 'OT/Nghỉ phép => Danh sách làm thêm giờ',
                    'description' => 'Nghỉ phép',
                    'url' => '/lam-them-gio-va-nghi-phep/lam-them-gio',
                    'screen_category_id' => $timeOff->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'OT/Nghỉ phép => Tạo/ Chỉnh sửa đơn làm thêm giờ',
                    'display_name' => 'OT/Nghỉ phép => Tạo/ Chỉnh sửa đơn làm thêm giờ',
                    'description' => 'Nghỉ phép',
                    'url' => '/lam-them-gio-va-nghi-phep/tao-hoac-chinh-sua-don-lam-them-gio',
                    'screen_category_id' => $timeOff->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'OT/Nghỉ phép =>Duyệt đơn làm thêm giờ',
                    'display_name' => 'OT/Nghỉ phép =>Duyệt đơn làm thêm giờ',
                    'description' => 'Nghỉ phép',
                    'url' => '/lam-them-gio-va-nghi-phep/duyet-lam-them-gio',
                    'screen_category_id' => $timeOff->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
                [
                    'name' => 'OT/Nghỉ phép => Thống kê',
                    'display_name' => 'OT/Nghỉ phép => Thống kê',
                    'description' => 'Nghỉ phép',
                    'url' => '/lam-them-gio-va-nghi-phep/thong-ke',
                    'screen_category_id' => $timeOff->id,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            ];

            Screen::query()->insert($screens);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DB Seed: ' . $e->getMessage());
        }
    }
}

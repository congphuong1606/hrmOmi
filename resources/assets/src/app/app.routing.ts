import {Routes, CanActivate, Router} from '@angular/router';
import {ImportPersonnelComponent} from './personnel/import/import-personnel.component';
import {ImportPersonnelFileDetailComponent} from './personnel/import-file-detail/import-personnel-file-detail.component';
import {ImportTimeKeepingComponent} from './timekeeping/import/import-timekeeping.component';
import {ImportTimeKeepingFileDetailComponent} from './timekeeping/import-file-detail/import-timekeeping-file-detail.component';

import {AuthLayoutComponent} from './layouts/auth/auth-layout.component';
import {AuthenticateComponent} from './authenticate/authenticate.component';
import {AuthComponent} from './layout/auth/auth.component';
import {Injectable} from '@angular/core';
import {AuthService} from './services/authSevice';
import {PersonnelListComponent} from './personnel/personnel-list/personnel-list.component';
import {ChangelListComponent} from './personnel/change-list/change-list.component';
import {ChangelDetailComponent} from './personnel/change-detail/change-detail.component';
import {PersonnelCreateComponent} from './personnel/create/personnel-create.component';
import {PersonnelDetailComponent} from './personnel/personnel-detail/personnel-detail.component';
import {PersonnelUpdateComponent} from './personnel/personnel-update/personnel-udpate.component';
import {TimeoffLateListComponent} from './timeoff/timeoff-late/list/timeoff-late-list.component';
import {DayoffListComponent} from './timeoff/dayoff/list/dayoff-list.component';
import {ApproveListComponent} from './timeoff/approve/approve-list.component';
import {ApproveTimekeepingUpdateComponent} from './timekeeping/approve-update/approve-timekeeping-update.component';
import {TotalTimekeepingComponent} from './timekeeping/total/total-timekeeping.component';
import {TotalTimekeepingUpdateComponent} from './timekeeping/total-update/total-timekeeping-update.component';
import {AcountComponent} from './governance/acountmanagement/acount.component';
import {OfficeComponent} from './governance/office/office.component';
import {JobStatusComponent} from './governance/jobstatus/jobstatus.component';
import {RoleComponent} from './governance/role/role.component';
import {UpdateRoleComponent} from './governance/role/role-update/role-update.component';
import {AddRoleComponent} from './governance/role/role-add/role-add.component';
import {AddOfficeComponent} from './governance/office/office-new/addoffice.component';
import {UpdateJobStatusComponent} from './governance/jobstatus/jobstatus-update/jobstatus-update.component';
import {AddjobStatusComponent} from './governance/jobstatus/jobstatus-add/jobstatus-add.component';
import {UpdateOfficeComponent} from './governance/office/office-update/office-update.component';
import {CategoryListComponent} from './category/list/category-list.component';
import {UserRoleComponent} from './governance/role/users-role/users-role.component';
import {AddUserRoleComponent} from './governance/role/users-role/add/add-users-role.component';
import {ScreenComponent} from './category/screen/screen.component';
import {AddScreenComponent} from './category/screen/screen-new/add-screen.component';
import {UpdateScreenComponent} from './category/screen/screen-update/screen-update.component';
import {PermissionComponent} from './governance/role/permisson-display/permisson.display.component';
import {DepartmentComponent} from './category/department/department.component';
import {AddDepartmentComponent} from './category/department/department-add/department-add.component';
import {UpdateDepartmentComponent} from './category/department/department-update/department-update.component';
import {OtherCategoryComponent} from './category/other-categories/other-categories.component';
import {UpdateOtherCategoryComponent} from './category/other-categories/other-categories-update/other-categories-update.component';
import {AddOtherCategoryComponent} from './category/other-categories/other-categories-add/other-categories-add.component';
import {HomeComponent} from './home/home.component';
import {HolidayComponent} from './category/official-holiday/holiday.component';
import {AddHolidayComponent} from './category/official-holiday/holiday-add/holiday-add.component';
import {UpdateHolidayComponent} from './category/official-holiday/holiday-update/holiday-update.component';
import {CourseComponent} from './trainning/course/course.component';
import {CourseManagementComponent} from './trainning/course-management/course.management.component';
import {OvertimeComponent} from './timeoff/over-time/overtime.component';
import {AddOvertimeComponent} from './timeoff/over-time/over-time-add/overtime-add.component';
import {ApproveOvertimeComponent} from './timeoff/over-time/approve-overtime/approve-overtime.component';
import {CreateCourseComponent} from './trainning/course-management/create-course/create-course.component';
import {UpdateCourseComponent} from './trainning/course-management/update/update-course.component';
import {CoreOfCourseComponent} from './trainning/course-management/core-of-course/core-of-course.component';
import {CheckInCheckOutListComponent} from './timekeeping/checkin-checkout-list/check-in-check-out-list.component';
import {CheckInCheckOutUpdateComponent} from './timekeeping/checkin-checkout-update/checkin-checkout-update.component';
import {SettingComponent} from './setting/setting.component';
import {RequestStatisticsComponent} from './timeoff/statistics/statistics.component';
import {MyInfoComponent} from './personnel/my-info/my-info.component';
import {UpdateMyInfoComponent} from './personnel/update-my-info/update-my-info.component';
import {CreateOrEditTimeOffComponent} from './timeoff/create-or-edit/create-or-edit-time-off.component';
import {MyTimekeepingComponent} from './timekeeping/self/my-timekeeping.component';
import {DemoComponent} from './demo-vung/demo';
import { ChangePasswordComponent } from './personnel/change-password/change-password.component';
import {IconsComponent} from './components/icons/icons.component';
import {PassResetComponent} from './password-reset/pass-reset.component';
import { PersonnelResourceComponent } from './personnel/personnel-resource/personnel-resource.component';
import { AccumulatedComponent } from './timekeeping/accumulated/accumulated.component';

@Injectable()
export class CanActiveDashboard implements CanActivate {
    constructor(private router: Router, private authService: AuthService) {

    }

    canActivate() {
        if (!this.authService.isTokenExpired()) {
            return true;
        }

        this.router.navigate(['/login']);
        return false;
    }
}

export const AppRoutes: Routes = [
    {
        path: '',
        component: AuthComponent,
        canActivate: [CanActiveDashboard],
        children: [
            {
                path: '',
                redirectTo: '/trang-chu',
                pathMatch: 'full',
            },
            {
                path: 'trang-chu',
                component: HomeComponent,
            },
            {
                path: 'cai-dat',
                component: SettingComponent,
            },

            {
                path: 'dashboard',
                loadChildren: './dashboard/dashboard.module#DashboardModule'
            },
            {
                path: 'components',
                loadChildren: './components/components.module#ComponentsModule'
            },
            {
                path: 'forms',
                loadChildren: './forms/forms.module#Forms'
            },
            {
                path: 'tables',
                loadChildren: './tables/tables.module#TablesModule'
            },
            {
                path: 'maps',
                loadChildren: './maps/maps.module#MapsModule'
            },
            {
                path: 'charts',
                loadChildren: './charts/charts.module#ChartsModule'
            },
            {
                path: 'calendar',
                loadChildren: './calendar/calendar.module#CalendarModule'
            },
            {
                path: '',
                loadChildren: './userpage/user.module#UserModule'
            },
            {
                path: '',
                loadChildren: './timeline/timeline.module#TimelineModule'
            },
            {
                path: '',
                component: AuthLayoutComponent,
                children: [{
                    path: 'pages',
                    loadChildren: './pages/pages.module#PagesModule'
                }]
            },
            {
                path: 'quan-tri',
                children: [
                    {
                        path: 'tai-khoan',
                        component: AcountComponent
                    },
                    {
                        path: 'quan-ly-phan-quyen',
                        component: RoleComponent,
                    },
                    {
                        path: 'cap-nhat-vai-tro',
                        component: UpdateRoleComponent,
                    },
                    {
                        path: 'them-vai-tro',
                        component: AddRoleComponent,
                    },
                    {
                        path: 'xem-nguoi-dung-trong-vai-tro/:id/:role-name',
                        component: UserRoleComponent,
                    },
                    {
                        path: 'them-nguoi-dung-trong-vai-tro/:id/:role-name',
                        component: AddUserRoleComponent,
                    },
                    {
                        path: 'thiet-lap-quyen-trong-vai-tro',
                        component: PermissionComponent,
                    },
                ]
            },
            {
                path: 'danh-sach-nhan-su',
                children: [
                    {
                        path: 'danh-sach',
                        component: PersonnelListComponent
                    },
                    {
                        path: 'nguon-luc',
                        component: PersonnelResourceComponent
                    },
                    {
                        path: 'quan-ly-thay-doi',
                        component: ChangelListComponent
                    },
                    {
                        path: 'chi-tiet-thay-doi/:id',
                        component: ChangelDetailComponent
                    },
                    {
                        path: 'them-nhan-su',
                        component: PersonnelCreateComponent
                    },
                    {
                        path: 'thong-tin-nhan-su/:id',
                        component: PersonnelDetailComponent
                    },
                    {
                        path: 'sua-thong-tin-nhan-su/:id',
                        component: PersonnelUpdateComponent
                    },
                    {
                        path: 'import',
                        component: ImportPersonnelComponent,
                    },
                    {
                        path: 'import/files/:id',
                        component: ImportPersonnelFileDetailComponent
                    },
                    {
                        path: 'ho-so',
                        component: MyInfoComponent
                    },
                    {
                        path: 'sua-ho-so',
                        component: UpdateMyInfoComponent
                    },
                    {
                        path: 'doi-mat-khau',
                        component: ChangePasswordComponent
                    },
                ]
            },
            {
                path: 'dao-tao',
                children: [
                    {
                        path: 'danh-sach-khoa-hoc',
                        component: CourseComponent
                    },
                    {
                        path: 'quan-ly-khoa-hoc',
                        component: CourseManagementComponent
                    }, {
                        path: 'tao-khoa-hoc',
                        component: CreateCourseComponent
                    }, {
                        path: 'cap-nhat-khoa-hoc',
                        component: UpdateCourseComponent
                    }
                    , {
                        path: 'quan-ly-nhan-vien-trong-khoa-hoc',
                        component: CoreOfCourseComponent,
                    }
                ]
            },
            {
                path: 'lam-them-gio-va-nghi-phep',
                children: [
                    {
                        path: 'tao-hoac-chinh-sua-don-vang-mat',
                        component: CreateOrEditTimeOffComponent,
                    },
                    {
                        path: 'di-muon-ve-som',
                        component: TimeoffLateListComponent
                    }
                    , {
                        path: 'lam-them-gio',
                        component: OvertimeComponent
                    }
                    , {
                        path: 'tao-hoac-chinh-sua-don-lam-them-gio',
                        component: AddOvertimeComponent
                    },
                    {
                        path: 'nghi-theo-ngay',
                        component: DayoffListComponent
                    },
                    {
                        path: 'duyet-nghi-phep',
                        component: ApproveListComponent
                    },
                  /*  {
                        path: 'duyet-lam-them-gio',
                        component: ApproveOvertimeComponent
                    },*/
                    {
                        path: 'thong-ke',
                        component: RequestStatisticsComponent,
                    },
                ]
            },
            {
                path: 'cham-cong',
                children: [
                    {
                        path: 'duyet-du-lieu',
                        component: ImportTimeKeepingComponent
                    },
                    {
                        path: 'duyet-du-lieu/:id',
                        component: ImportTimeKeepingFileDetailComponent
                    },
                    {
                        path: 'duyet-du-lieu/files/:id',
                        component: ImportTimeKeepingFileDetailComponent
                    },
                    {
                        path: 'sua-du-lieu/:id',
                        component: ApproveTimekeepingUpdateComponent
                    },
                    {
                        path: 'check-in-check-out',
                        component: CheckInCheckOutListComponent
                    },
                    {
                        path: 'sua-checkin-checkout/:id',
                        component: CheckInCheckOutUpdateComponent
                    },
                    {
                        path: 'bang-tong',
                        component: TotalTimekeepingComponent
                    },
                    {
                        path: 'sua-bang-tong/:id',
                        component: TotalTimekeepingUpdateComponent
                    },
                    {
                        path: 'bang-ca-nhan',
                        component: MyTimekeepingComponent
                    },
                    {
                        path: 'tich-luy-tong',
                        component: AccumulatedComponent
                    },
                ]
            },
            {
                path: 'danh-muc',
                children: [
                    {
                        path: 'danh-muc-khac',
                        component: CategoryListComponent,
                    },
                    {
                        path: 'danh-sach-trong-danh-muc-khac/:category-name',
                        component: OtherCategoryComponent,
                    },
                    {
                        path: 'cap-nhat-danh-muc-khac/:category-name',
                        component: UpdateOtherCategoryComponent,
                    },
                    {
                        path: 'them-danh-muc-khac/:category-name',
                        component: AddOtherCategoryComponent,
                    },
                    {
                        path: 'phong-ban',
                        component: DepartmentComponent,
                    },
                    {
                        path: 'them-phong-ban',
                        component: AddDepartmentComponent,
                    },
                    {
                        path: 'cap-nhat-phong-ban',
                        component: UpdateDepartmentComponent,
                    },
                    {
                        path: 'cac-ngay-nghi-le',
                        component: HolidayComponent,
                    },
                    {
                        path: 'them-cac-ngay-nghi-le',
                        component: AddHolidayComponent,
                    },
                    {
                        path: 'cap-nhat-cac-ngay-nghi-le',
                        component: UpdateHolidayComponent,
                    },
                    {
                        path: 'trang-thai-cong-viec',
                        component: JobStatusComponent
                    },
                    {
                        path: 'them-trang-thai-cong-viec',
                        component: AddjobStatusComponent,
                    },
                    {
                        path: 'cap-nhat-trang-thai-cong-viec',
                        component: UpdateJobStatusComponent,
                    },
                    {
                        path: 'chuc-danh',
                        component: OfficeComponent
                    },
                    {
                        path: 'them-chuc-danh',
                        component: AddOfficeComponent,
                    },
                    {
                        path: 'cap-nhat-chuc-danh',
                        component: UpdateOfficeComponent,
                    },
                    {
                        path: 'man-hinh',
                        component: ScreenComponent,
                    }, {
                        path: 'them-danh-muc-man-hinh',
                        component: AddScreenComponent,
                    },
                    {
                        path: 'cap-nhat-danh-muc-man-hinh',
                        component: UpdateScreenComponent,
                    },
                ]

            }]
    },
    {
        path: 'login',
        component: AuthenticateComponent
    },
    {
        path: 'password_reset',
        component: PassResetComponent
    },
    {
        path: 'icons',
        component: IconsComponent
    },
];

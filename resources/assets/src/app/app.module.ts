import {NgModule} from '@angular/core';
import {BrowserModule} from '@angular/platform-browser';
import {RouterModule} from '@angular/router';
import {HttpModule} from '@angular/http';
import {FormsModule} from '@angular/forms';
import {HTTP_INTERCEPTORS} from '@angular/common/http';
import {AppComponent} from './app.component';
import {SidebarModule} from './sidebar/sidebar.module';
import {FixedPluginModule} from './shared/fixedplugin/fixedplugin.module';
import {FooterModule} from './shared/footer/footer.module';
import {NavbarModule} from './shared/navbar/navbar.module';
import {AdminLayoutComponent} from './layouts/admin/admin-layout.component';
import {AuthLayoutComponent} from './layouts/auth/auth-layout.component';
import {AppRoutes, CanActiveDashboard} from './app.routing';
import {PersonnelListComponent} from './personnel/personnel-list/personnel-list.component';
import {ChangelListComponent} from './personnel/change-list/change-list.component';
import {ChangelDetailComponent} from './personnel/change-detail/change-detail.component';
import {PersonnelCreateComponent} from './personnel/create/personnel-create.component';
import {PersonnelDetailComponent} from './personnel/personnel-detail/personnel-detail.component';
import {PersonnelUpdateComponent} from './personnel/personnel-update/personnel-udpate.component';
import {TimeoffLateListComponent} from './timeoff/timeoff-late/list/timeoff-late-list.component';
import {DayoffListComponent} from './timeoff/dayoff/list/dayoff-list.component';
import {ApproveListComponent} from './timeoff/approve/approve-list.component';
import {ApproveTimekeepingComponent} from './timekeeping/approve/approve-timekeeping.component';
import {ApproveTimekeepingUpdateComponent} from './timekeeping/approve-update/approve-timekeeping-update.component';
import {TotalTimekeepingComponent} from './timekeeping/total/total-timekeeping.component';
import {TotalTimekeepingUpdateComponent} from './timekeeping/total-update/total-timekeeping-update.component';
import {CategoryListComponent} from './category/list/category-list.component';
import {AuthenticateComponent} from './authenticate/authenticate.component';
import {AuthComponent} from './layout/auth/auth.component';
import {ReactiveFormsModule} from '@angular/forms';
import {HttpClientModule} from '@angular/common/http';
import {AuthService} from './services/authSevice';
import {AcountComponent} from './governance/acountmanagement/acount.component';
import {OfficeComponent} from './governance/office/office.component';
import {JobStatusComponent} from './governance/jobstatus/jobstatus.component';
import {RoleComponent} from './governance/role/role.component';
import {AddRoleComponent} from './governance/role/role-add/role-add.component';
import {UpdateRoleComponent} from './governance/role/role-update/role-update.component';
import {AddOfficeComponent} from './governance/office/office-new/addoffice.component';
import {UpdateJobStatusComponent} from './governance/jobstatus/jobstatus-update/jobstatus-update.component';
import {AddjobStatusComponent} from './governance/jobstatus/jobstatus-add/jobstatus-add.component';
import {UpdateOfficeComponent} from './governance/office/office-update/office-update.component';
import {UserRoleComponent} from './governance/role/users-role/users-role.component';
import {AddUserRoleComponent} from './governance/role/users-role/add/add-users-role.component';
import {TokenInterceptor} from './services/TokenInterceptor';
import {ScreenComponent} from './category/screen/screen.component';
import {AddScreenComponent} from './category/screen/screen-new/add-screen.component';
import {UpdateScreenComponent} from './category/screen/screen-update/screen-update.component';
import {PersonnelService} from './services/personnel.service';
import {RoleService} from './services/role.service';
import {ScreenCategoryService} from './services/category/screen.service';
import {PermisionService} from './services/permision.service';
import {JobStatusService} from './services/category/job.status.service';
import {OfficeService} from './services/category/office.service';
import {PermissionComponent} from './governance/role/permisson-display/permisson.display.component';
import {FileValueAccessorDirective} from './validation/file-value-accessor';
import {safeBase64} from './pipe/safe-base64';
import {TimeOffService} from './services/time-off/TimeOff.service';
import {LoaderComponent} from './shared/loader/loader.component';
import {DataGlobalService} from './services/data.global.service';
import {HomeComponent} from './home/home.component';
import {DepartmentComponent} from './category/department/department.component';
import {AddDepartmentComponent} from './category/department/department-add/department-add.component';
import {UpdateDepartmentComponent} from './category/department/department-update/department-update.component';
import {CategoryService} from './services/category/category.service';
import {PersonnelSkillModelComponent} from './personnel/modal-skill/personnel-skill-modal.component';
import {NgxDatatableModule} from '@swimlane/ngx-datatable';
import {OtherCategoryComponent} from './category/other-categories/other-categories.component';
import {UpdateOtherCategoryComponent} from './category/other-categories/other-categories-update/other-categories-update.component';
import {AddOtherCategoryComponent} from './category/other-categories/other-categories-add/other-categories-add.component';
import {ImportPersonnelComponent} from './personnel/import/import-personnel.component';
import {ImportPersonnelFileDetailComponent} from './personnel/import-file-detail/import-personnel-file-detail.component';
import {CategoryOtherService} from './services/category/category-other.service';
import {ImportTimeKeepingComponent} from './timekeeping/import/import-timekeeping.component';
import {ImportTimeKeepingFileDetailComponent} from './timekeeping/import-file-detail/import-timekeeping-file-detail.component';
import {HolidayComponent} from './category/official-holiday/holiday.component';
import {UpdateHolidayComponent} from './category/official-holiday/holiday-update/holiday-update.component';
import {AddHolidayComponent} from './category/official-holiday/holiday-add/holiday-add.component';
import {CourseComponent} from './trainning/course/course.component';
import {CourseManagementComponent} from './trainning/course-management/course.management.component';
import {DapickerFormInputComponent} from './shared/datepicker/datepicker-form-input.component';
import {OvertimeComponent} from './timeoff/over-time/overtime.component';
import {AddOvertimeComponent} from './timeoff/over-time/over-time-add/overtime-add.component';
import {OvertimeService} from './services/overtime.service';
// import {ApproveOvertimeComponent} from './timeoff/over-time/approve-overtime/approve-overtime.component';
import {CreateCourseComponent} from './trainning/course-management/create-course/create-course.component';
import {CourseService} from './services/course/course.service';
import {UpdateCourseComponent} from './trainning/course-management/update/update-course.component';
import {CoreOfCourseComponent} from './trainning/course-management/core-of-course/core-of-course.component';
import {CheckInCheckOutListComponent} from './timekeeping/checkin-checkout-list/check-in-check-out-list.component';
import {CheckInCheckOutUpdateComponent} from './timekeeping/checkin-checkout-update/checkin-checkout-update.component';
import {SettingComponent} from './setting/setting.component';
import {SettingService} from './services/setting.service';
import {RequestStatisticsComponent} from './timeoff/statistics/statistics.component';
import { DateFormatVNPipe, DayOfWeekPipe, HourFormatWithoutSecondPipe, TimeOffStatusPipe } from './pipe/common.pipe';
import { MyInfoComponent } from './personnel/my-info/my-info.component';
import { UpdateMyInfoComponent } from './personnel/update-my-info/update-my-info.component';
import {CreateOrEditTimeOffComponent} from './timeoff/create-or-edit/create-or-edit-time-off.component';
import { MyTimekeepingComponent } from './timekeeping/self/my-timekeeping.component';
import { FileValidator } from './validation/file-validator';
import { DashboardComponent } from './dashboard/dashboard.component';
import { UserComponent } from './user/user.component';
import { UpgradeComponent } from './upgrade/upgrade.component';
import { TypographyComponent } from './typography/typography.component';
import { TableComponent } from './table/table.component';
import { NotificationsComponent } from './notifications/notifications.component';
import { MapsComponent } from './maps/maps.component';
// import { IconsComponent } from './icons/icons.component';
import {HomeService} from './services/home.service';
import {FcmserviceService} from './services/fcm/fcmservice.service';
import * as firebase from 'firebase';
import {firebaseConfig} from '../environments/firebase.config';
import {IconsComponent} from './components/icons/icons.component';
import {DemoComponent} from './demo-vung/demo';
import { UpdateCheckinCheckoutModalComponent } from './timekeeping/modal-update-checkin-checkout/update-checkin-checkout-modal.component';
import { ChangePasswordComponent } from './personnel/change-password/change-password.component';
import { ClickOutsideModule } from 'ng4-click-outside';
import {PassResetComponent} from './password-reset/pass-reset.component';
import { PersonnelResourceComponent } from './personnel/personnel-resource/personnel-resource.component';
import { UpdateDirectManagerModalComponent } from './personnel/modal-update-direct-manager/update-direct-manager-modal.component';
import { UpdateProjectManagerModalComponent } from './personnel/modal-update-project-manager/update-project-manager-modal.component';
import { AccumulatedComponent } from './timekeeping/accumulated/accumulated.component';
import { UpdateAccumulatedModalComponent } from './timekeeping/modal-update-accumulated/update-accumulated-modal.component';
import { UpdateNoteModalComponent } from './timekeeping/modal-update-note/update-note-modal.component';


firebase.initializeApp(firebaseConfig);

@NgModule({
    imports: [
        BrowserModule,
        FormsModule,
        RouterModule.forRoot(AppRoutes),
        HttpModule,
        SidebarModule,
        NavbarModule,
        FooterModule,
        FixedPluginModule,
        ReactiveFormsModule,
        HttpClientModule,
        NgxDatatableModule,
        ClickOutsideModule,
        // AngularFireModule.initializeApp(firebaseConfig),
    ],
    declarations: [
        AppComponent,
        AdminLayoutComponent,
        AuthLayoutComponent,
        AuthenticateComponent,
        AuthComponent,
        PersonnelListComponent,
        ChangelListComponent,
        ChangelDetailComponent,
        PersonnelCreateComponent,
        PersonnelDetailComponent,
        PersonnelUpdateComponent,
        TimeoffLateListComponent,
        DayoffListComponent,
        ApproveListComponent,
        ApproveTimekeepingComponent,
        ApproveTimekeepingUpdateComponent,
        AcountComponent,
        OfficeComponent,
        JobStatusComponent,
        AddOfficeComponent,
        UpdateOfficeComponent,
        RoleComponent,
        AddRoleComponent,
        UpdateRoleComponent,
        TotalTimekeepingComponent,
        CheckInCheckOutListComponent,
        CheckInCheckOutUpdateComponent,
        TotalTimekeepingUpdateComponent,
        UpdateJobStatusComponent,
        AddjobStatusComponent,
        CategoryListComponent,
        UserRoleComponent,
        AddUserRoleComponent,
        ScreenComponent,
        AddScreenComponent,
        UpdateScreenComponent,
        PermissionComponent,
        FileValueAccessorDirective,
        safeBase64,
        DateFormatVNPipe,
        LoaderComponent,
        HomeComponent,
        DepartmentComponent,
        AddDepartmentComponent,
        UpdateDepartmentComponent,
        PersonnelSkillModelComponent,
        UpdateOtherCategoryComponent,
        OtherCategoryComponent,
        AddOtherCategoryComponent,
        HomeComponent,
        HolidayComponent,
        UpdateHolidayComponent,
        AddHolidayComponent,
        ImportPersonnelComponent,
        ImportPersonnelFileDetailComponent,
        ImportTimeKeepingComponent,
        ImportTimeKeepingFileDetailComponent,
        CourseComponent,
        CourseManagementComponent,
        DapickerFormInputComponent,
        OvertimeComponent,
        AddOvertimeComponent,
        // ApproveOvertimeComponent,
        CreateCourseComponent,
        UpdateCourseComponent,
        CoreOfCourseComponent,
        SettingComponent,
        RequestStatisticsComponent,
        MyInfoComponent,
        UpdateMyInfoComponent,
        CreateOrEditTimeOffComponent,
        MyTimekeepingComponent,
        UpdateMyInfoComponent,
        FileValidator,
        DayOfWeekPipe,
        HourFormatWithoutSecondPipe,
        DashboardComponent,
        MapsComponent,
        NotificationsComponent,
        TableComponent,
        TypographyComponent,
        UpgradeComponent,
        UserComponent,
        IconsComponent,
        DemoComponent,
        UpdateCheckinCheckoutModalComponent,
        ChangePasswordComponent,
        TimeOffStatusPipe,
        PassResetComponent,
        PersonnelResourceComponent,
        UpdateDirectManagerModalComponent,
        UpdateProjectManagerModalComponent,
        AccumulatedComponent,
        UpdateAccumulatedModalComponent,
        UpdateNoteModalComponent,

    ],
    providers: [
        DataGlobalService,
        RoleService,
        OfficeService,
        JobStatusService,
        PermisionService,
        ScreenCategoryService,
        CanActiveDashboard,
        CategoryService,
        CategoryOtherService,
        AuthService,
        TimeOffService,
        OvertimeService,
        {
            provide: HTTP_INTERCEPTORS,
            useClass: TokenInterceptor,
            multi: true
        },
        PersonnelService,
        CourseService,
        SettingService,
        HomeService,
        FcmserviceService,
    ],
    bootstrap: [AppComponent]
})

export class AppModule {
}

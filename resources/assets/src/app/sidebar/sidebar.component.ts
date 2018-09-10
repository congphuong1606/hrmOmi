import {Component, OnInit, AfterViewInit, AfterViewChecked, AfterContentInit} from '@angular/core';
import {DataGlobalService} from '../services/data.global.service';
import {ActivatedRoute, Route, Router} from '@angular/router';


declare var $: any;

//Metadata
export interface RouteInfo {
    path: string;
    title: string;
    type: string;
    icontype: string;
    isPermission: boolean;
    // icon: string;
    children?: ChildrenItems[];
    id?: string;
}

export interface ChildrenItems {
    path: string;
    title: string;
    ab: string;
    type?: string;
    isPermission: boolean;
}

export const URLS_NAME: string = 'jwt_urls';
//Menu Items
export const ROUTES: RouteInfo[] = [
    {
        path: '/quan-tri',
        title: 'Quản trị',
        type: 'sub',
        icontype: 'icon-slide quantri',
        isPermission: false,
        children: [
            {path: 'quan-ly-phan-quyen', title: 'Quản lý phân quyền', ab: 'sub-icon-slide quantri1 ', isPermission: false},
        ]
    },
    {
        path: '/danh-sach-nhan-su',
        title: 'Nhân sự',
        type: 'sub',
        icontype: 'icon-slide dsns',
        isPermission: false,
        children: [
            {path: 'danh-sach', title: 'Danh sách', ab: 'sub-icon-slide dsns1', isPermission: false,},
            {path: 'nguon-luc', title: 'Nguồn lực', ab: 'sub-icon-slide dsns1', isPermission: false,},
            {path: 'quan-ly-thay-doi', title: 'Quản lý thay đổi ', ab: 'sub-icon-slide dsns2 ', isPermission: false,},
            {path: 'import', title: 'Import', ab: 'sub-icon-slide dsns3', isPermission: false,},
            {path: 'ho-so', title: 'Hồ sơ của tôi', ab: 'sub-icon-slide dsns4', isPermission: false,},
            {path: 'sua-ho-so', title: 'Sửa hồ sơ', ab: 'sub-icon-slide dsns5', isPermission: false,},
            {path: 'doi-mat-khau', title: 'Đổi mật khẩu', ab: 'sub-icon-slide dsns5', isPermission: false,},
        ]
    }
    ,
    {
        path: '/dao-tao',
        title: 'Đào tạo',
        type: 'sub',
        icontype: 'icon-slide daotao',
        isPermission: false,
        children: [
            {path: 'danh-sach-khoa-hoc', title: 'Danh sách khóa học', ab: 'sub-icon-slide daotao1 ', isPermission: false,},
            {path: 'quan-ly-khoa-hoc', title: 'Quản lý khóa học', ab: 'sub-icon-slide daotao2 ', isPermission: false,},

        ]
    },
    {
        path: '/lam-them-gio-va-nghi-phep',
        title: 'OT / Nghỉ phép',
        type: 'sub',
        icontype: 'icon-slide ottoff',
        isPermission: false,
        children: [
            {path: 'lam-them-gio', title: 'Làm ngoài giờ', ab: 'sub-icon-slide ottoff1 ', isPermission: false},
            {path: 'di-muon-ve-som', title: 'Ra ngoài/Muộn/Về sớm', ab: 'sub-icon-slide ottoff2 ', isPermission: false},
            {path: 'nghi-theo-ngay', title: 'Nghỉ/Quên chấm công', ab: 'sub-icon-slide ottoff3 ', isPermission: false},
            {path: 'duyet-nghi-phep', title: 'Duyệt nghỉ phép', ab: 'sub-icon-slide ottoff4 ', isPermission: false},
            // {path: 'duyet-lam-them-gio', title: 'Duyệt OT', ab: 'sub-icon-slide ottoff5 ', isPermission: false},
            {path: 'thong-ke', title: 'Thống kê', ab: 'sub-icon-slide ottoff6 ', isPermission: false},
        ]
    },
    {
        path: '/cham-cong',
        title: 'Chấm công',
        type: 'sub',
        icontype: 'icon-slide chamcong',
        isPermission: false,
        children: [
            {path: 'duyet-du-lieu', title: 'Duyệt dữ liệu chấm công', ab: 'sub-icon-slide chamcong1 ', isPermission: false,},
            {path: 'check-in-check-out', title: 'Dữ liệu chấm công', ab: 'sub-icon-slide chamcong2 ', isPermission: false,},
            {path: 'tich-luy-tong', title: 'Quản lý ngày phép', ab: 'sub-icon-slide chamcong3 ', isPermission: false,},
            {path: 'bang-tong', title: 'Bảng chấm công tổng', ab: 'sub-icon-slide chamcong3 ', isPermission: false,},
            {path: 'bang-ca-nhan', title: 'Bảng chấm công của tôi', ab: 'sub-icon-slide chamcong3 ', isPermission: false,},
        ]
    }, {
        path: '/danh-muc',
        title: 'Danh Mục',
        type: 'sub',
        icontype: 'icon-slide danhmuc',
        isPermission: false,
        children: [
            {path: 'man-hinh', title: 'Màn hình', ab: 'sub-icon-slide danhmuc1', isPermission: false},
            {path: 'chuc-danh', title: 'Chức danh', ab: 'sub-icon-slide danhmuc2 ', isPermission: false},
            {title: 'Phòng ban', path: 'phong-ban', ab: 'sub-icon-slide danhmuc3 ', isPermission: false},
            {path: 'trang-thai-cong-viec', title: 'Trạng thái công việc', ab: 'sub-icon-slide danhmuc4 ', isPermission: false},
            {title: 'Các ngày nghỉ lễ', path: 'cac-ngay-nghi-le', ab: 'sub-icon-slide danhmuc5 ', isPermission: false},
            {path: 'danh-muc-khac', title: 'Danh mục khác', ab: 'sub-icon-slide danhmuc6 ', isPermission: false},

        ]
    }
];

@Component({
    moduleId: module.id,
    selector: 'sidebar-cmp',
    templateUrl: 'sidebar.component.html',
})

export class SidebarComponent {
    public menuItems: any[];
    public isLoadedData = false;
    public roleUrls: string[] = [];

    constructor(public globalService: DataGlobalService, private router: Router) {

    }

    ngOnInit() {
        var isWindows = navigator.platform.indexOf('Win') > -1 ? true : false;
        this.menuItems = this.defineRouter(ROUTES);
        this.isLoadedData = true;
        isWindows = navigator.platform.indexOf('Win') > -1 ? true : false;
        if (isWindows) {
            // if we are on windows OS we activate the perfectScrollbar function
            // $('.sidebar .sidebar-wrapper, .main-panel').perfectScrollbar();
            $('.sidebar .sidebar-wrapper').perfectScrollbar();
            $('html').addClass('perfect-scrollbar-on');
        } else {
            $('html').addClass('perfect-scrollbar-off');
        }
    }

    goToUserInfor() {
        this.router.navigate(['/danh-sach-nhan-su/ho-so']);
    }

    goToSettingScreen() {
        this.router.navigate(['/cai-dat']);
    }

    isNotMobileMenu() {
        if ($(window).width() > 991) {
            return false;
        }
        return true;
    }


    logOut(): void {
        this.globalService.logOut().subscribe(
            data => {
                localStorage.clear();
                this.router.navigate(['../login']);
            }, error1 => {
                this.globalService.log(error1);
            }
        );
    }

    defineRouter(routers: RouteInfo[]): any[] {
        for (let menuitem of routers) {
            menuitem.isPermission = this.checkPemisson(menuitem.path);
            if (menuitem.type === 'sub') {
                for (let childitem of menuitem.children) {
                    childitem.isPermission = this.checkPemisson(menuitem.path + '/' + childitem.path);
                }
            }
        }
        return routers;
    }

    ngAfterViewInit() {
        var $sidebarParent = $('.sidebar .nav > li.active .collapse li.active > a').parent().parent().parent();
        var collapseId = $sidebarParent.siblings('a').attr('href');
        $(collapseId).collapse('show');

    }

    checkPemisson(path: string): boolean {
        return this.globalService.checkPemisson(path);
    }
}

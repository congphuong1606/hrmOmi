import {Component, OnInit, OnDestroy} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log, error} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../services/authSevice';
import {Router, NavigationEnd} from '@angular/router';
import {Role, ListRoleResponse} from '../../models/api/response/RoleReponse';
import {RoleService} from '../../services/role.service';
import {SidebarComponent} from '../../sidebar/sidebar.component';
import {DataGlobalService} from '../../services/data.global.service';
import {Subscription} from 'rxjs/Subscription';

declare var $: any;
declare var swal: any;

export const URLS_NAME: string = 'jwt_urls';

@Component({
    selector: 'role-cmp',
    moduleId: module.id,
    templateUrl: 'role.component.html'
})


export class RoleComponent implements OnInit, OnDestroy {
    check = false;
    form: FormGroup;
    dataRows: Role[];

    roleResponse: ListRoleResponse = {
        status: '',
        message: '',
        roles: []
    };
    columns: string[];
    columnUsers: string[];
    subscriptionDataRows: Subscription;
    subscriptionRemove: Subscription;
    isAdmin = false;
    allowUpdate = false;
    allowAdd = false;
    allowViewUser = false;
    allowSettingPer = false;


    setupData(): void {
        this.isAdmin = this.dataGlobalService.isAdmin();
        this.allowUpdate = this.checkPemision('/quan-tri/cap-nhat-vai-tro');
        this.allowAdd = this.checkPemision('/quan-tri/them-vai-tro');
        this.allowViewUser = this.checkPemision('/quan-tri/xem-nguoi-dung-trong-vai-tro');
        this.allowSettingPer = this.checkPemision('/quan-tri/thiet-lap-quyen-trong-vai-tro');

    }

    ngOnDestroy(): void {
        this.subscriptionDataRows !== undefined ? this.subscriptionDataRows.unsubscribe() : console.log(':D');
        this.subscriptionRemove !== undefined ? this.subscriptionRemove.unsubscribe() : console.log(':D');
    }

    constructor(private router: Router,
                private roleService: RoleService,
                private dataGlobalService: DataGlobalService) {

    }

    ngOnInit() {
        if (this.checkPemision('/quan-tri/quan-ly-phan-quyen')) {
            this.setupData();
            this.getList();
        } else {
            window.history.back();
        }

    }

    addRoleAction(): void {
        this.router.navigate(['../quan-tri/them-vai-tro']);
    }

    edtRole(data: any): void {
        this.router.navigate(['../quan-tri/cap-nhat-vai-tro'], {queryParams: data});
    }

    ruleRole(data: any): void {
        this.router.navigate(['../quan-tri/thiet-lap-quyen-trong-vai-tro'], {queryParams: data});
    }

    showListUserOfRole(data: any): void {
        this.router.navigate(['../quan-tri/xem-nguoi-dung-trong-vai-tro',
            data.id, data.name]);
    }

    getList(): void {
        this.subscriptionDataRows = this.roleService.getListRole().subscribe(
            data => {
                if (data.status === 'success') {
                    this.check = true;
                    this.dataRows = (data as ListRoleResponse).roles;
                    this.dataRows.length > 0 ? this.finAdmin() : this.creatAdmin();

                }
            },
            error => {
                this.check = true;
                this.dataGlobalService.actionFail(error.error);
            },
        );
    }

    finAdmin(): void {
        let flag = 0;
        this.dataRows.forEach(element => {
            if (element.name === 'ADMIN') {
                flag = 1;
            }
        });
    }

    creatAdmin(): void {
        const bodyAdmin = {
            name: 'ADMIN',
            display_name: 'ADMIN',
            description: 'Người dùng có tất cả các quyền trong hệ thống',
        };
        this.roleService.createRole(bodyAdmin).subscribe(
            data => {
                if (data.status === 'success') {
                    this.dataRows.push(data.role as Role);
                }
            },
            error => {
                this.dataGlobalService.actionFail(error.error);
            }
        );
    }

    deleteRole(rl: any): void {
        swal({
            title: 'Xác nhận xóa?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(function () {
                        resolve();
                    }, 500);
                });
            },
            allowOutsideClick: false
        }).then(result => {
            this.subscriptionRemove = this.roleService.deleteRole(rl.id).subscribe(
                data => {
                    this.dataRows = this.dataRows.filter(h => h !== rl);
                    swal('Đã Xóa!', 'Bạn đã xóa nhóm quyền thành công ', 'success');
                },
                error => {
                    this.check = true;
                    console.log(error.error)
                    this.dataGlobalService.actionFail(error.error);
                });
        });
    }

    checkPemision(path: string): boolean {
        return this.dataGlobalService.checkPemisson(path);
    }

    isLoadSuccess(): boolean {
        if (this.check) {
            $('[rel="tooltip"]').tooltip();
        }
        return this.check;
    }
}
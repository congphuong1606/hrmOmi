import {Component, OnInit, OnDestroy} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../../services/authSevice';
import {Router, ActivatedRoute, ParamMap, NavigationEnd} from '@angular/router';

import {Role, User} from '../../../models/api/response/RoleReponse';
import {RoleService} from '../../../services/role.service';
import {DataGlobalService} from '../../../services/data.global.service';
import {Subscription} from 'rxjs/Subscription';

declare var $: any;
declare var swal: any;

declare interface TableData {
    headerRow: string[];
    dataRows: User[];
}

@Component({
    selector: 'users-role-cmp',
    moduleId: module.id,
    templateUrl: 'users-role.component.html'
})

export class UserRoleComponent implements OnInit, OnDestroy {
    id: number;
    curentPage = 1;
    isInverse = 0;
    searchData = '';
    totalUser = 0;
    perPage = 15;
    lastPage = 1;
    listUser: User[] = [];
    roleName = '';
    check = false;
    form: FormGroup;
    arrayPage: number[] = [];

    UserTableData: TableData = {
        headerRow: [],
        dataRows: []
    };

    subscription: Subscription;
    subscriptionDataRows: Subscription;
    subscriptionDataRemove: Subscription;

    ngOnDestroy(): void {
        this.subscriptionDataRows !== undefined ? this.subscriptionDataRows.unsubscribe() : console.log(':D');
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');
        this.subscriptionDataRemove !== undefined ? this.subscriptionDataRemove.unsubscribe() : console.log(':D');
    }

    constructor(private router: Router,
                private route: ActivatedRoute,
                private dataGlobalService: DataGlobalService,
                private roleService: RoleService) {
    }

    // DỢI DỮ LIỆU LOAD XONG SẼ HIỂN THỊ TRANG


    ngOnInit() {
        this.route.paramMap.subscribe((params: ParamMap) => {
            this.id = parseInt(params.get('id'));
            this.roleName = params.get('role-name');
            console.log(this.id + ':' + this.roleName);
        });
        this.subscription = this.router.events.subscribe(val => {
            if (val instanceof NavigationEnd) {
                this.getQueryParamRouter();
                if (this.checkPemision('/quan-tri/xem-nguoi-dung-trong-vai-tro')) {
                    if (this.id != null) {
                        this.getListUser();
                    }
                } else {
                    window.history.back();
                }
            }
        });
        this.getQueryParamRouter();
        if (this.checkPemision('/quan-tri/xem-nguoi-dung-trong-vai-tro')) {
            if (this.id != null) {
                this.getListUser();
            }
        } else {
            window.history.back();
        }

    }

    checkPemision(path: string): boolean {
        return this.dataGlobalService.checkPemisson(path);
    }

    getQueryParamRouter() {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.curentPage = parseInt(queryParamMap.get('index_page') === null ? '1' : queryParamMap.get('index_page'));
    }


    getListUser(): void {
        this.check = false;
        this.subscriptionDataRows = this.roleService.getListUserWithRoleID(this.id,
            this.isInverse, this.curentPage, this.searchData, this.perPage).subscribe(
            repo => {
                this.UserTableData.dataRows = (repo.users as User[]);
                this.lastPage = (repo.pagination.last_page as number);
                this.totalUser = (repo.pagination.total as number);
                this.curentPage = (repo.pagination.current_page as number);
                this.arrayPage.length = 0;
                for (let i = 1; i <= this.lastPage; i++) {
                    this.arrayPage.push(i);
                }
                this.check = true;
                $('[rel="tooltip"]').tooltip();
            },
            error => {
                this.check = true;
                this.dataGlobalService.actionFail(error.error);
            }
        );

    }


    deleteUserOfRole(user: User): void {
        swal({
            title: 'Xác nhận xóa?',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
        }).then(isConfirm => {
            if (isConfirm) {
                this.subscriptionDataRemove = this.roleService.deleteRoleUser(this.id, user.id).subscribe(res => {
                        console.log(res);
                        this.UserTableData.dataRows = this.UserTableData.dataRows.filter(h => h !== user);
                        this.getListUser();
                        swal(
                            'Thành công!',
                            'Bạn đã xóa user khỏi nhóm quyền này',
                            'success'
                        );
                    },
                    error => {
                        this.dataGlobalService.actionFail(error.error);
                    }
                );

            }
        });
    }

    addUserOfRole(): void {
        this.ngOnDestroy();
        this.router.navigate(['../quan-tri/them-nguoi-dung-trong-vai-tro', this.id, this.roleName]);
    }

    getRandomString() {
        let text = '';
        const possible = 'abcdefghijklmnopqrstuvwxyz0123456789';

        for (let i = 0; i < 5; i++) {
            text += possible.charAt(Math.floor(Math.random() * possible.length));
        }

        return text;
    }

    onSearchChange(searchValue: string) {
        this.searchData = searchValue.trim();
    }

    actionSearch(): void {
        this.UserTableData.dataRows.length = 0;
        this.getListUser();
    }

    onChangPage(index: number, serData: string): void {
        this.curentPage = index;
        this.searchData = serData;
        this.refreshPage();
    }

    refreshPage(): void {
        this.router.navigate(['/quan-tri/xem-nguoi-dung-trong-vai-tro/', this.id, this.roleName],
            {
                queryParams: {
                    index_page: this.curentPage,
                    search_data: this.searchData.trim(),
                    redirect_id: this.getRandomString()
                }
            });
    }

    onBack(): void {
        this.ngOnDestroy();
        window.history.back();

    }
}

import {Component, OnDestroy, OnInit} from '@angular/core';
import {CommonModule} from '@angular/common';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../../../services/authSevice';
import {Router, ActivatedRoute, ParamMap} from '@angular/router';
import {ScreenCategoryService} from '../../../services/category/screen.service';
import {Role} from '../../../models/api/response/RoleReponse';
import {RoleService} from '../../../services/role.service';
import {PermisionService} from '../../../services/permision.service';
import {ListScreenRepo, ScreenCategoryRepo, ListScrCategory, Screen} from '../../../models/api/category-response/ScreenReponse';
import {listPermissonRepo} from '../../../models/api/response/PermissionReponse';
import {DataGlobalService} from '../../../services/data.global.service';
import {Subscription} from 'rxjs/Subscription';

declare var swal: any;

declare interface Show {
    id: string;
    name: string;
    isShow: boolean;
}


@Component({
    selector: 'permisson.-display-cmp',
    moduleId: module.id,
    templateUrl: 'permisson.display.component.html'
})

export class PermissionComponent implements OnInit, OnDestroy {

    listShow: Show[] = [];
    iconCategory: string[] = [];
    role: Role = new Role();
    id: number;
    selects: any[] = [];
    idSelect = '';
    select: any;

    listCategory: listPermissonRepo = {
        status: '',
        message: '',
        screen_categories: [],
    };
    listScreenSelected: Screen[] = [];
    sub1: Subscription;
    sub2: Subscription;
    sub3: Subscription;
    form: FormGroup;
    ngOnDestroy(): void {
        this.sub1 !== undefined ? this.sub1.unsubscribe() : console.log('');
        this.sub2 !== undefined ? this.sub2.unsubscribe() : console.log('');
        this.sub3 !== undefined ? this.sub3.unsubscribe() : console.log('');
    }


    constructor(private router: Router,
                private route: ActivatedRoute,
                private roleService: RoleService,
                private dataGlobalService: DataGlobalService,
                private screenService: ScreenCategoryService,
                private permissionService: PermisionService) {
    }



    ngOnInit() {
        this.getQueryParamRouter();

        if (this.checkPemision('/quan-tri/thiet-lap-quyen-trong-vai-tro')) {
            this.getlistScreenSelected();
            this.getListCategory();
        } else {
            window.history.back();
        }
    }

    checkPemision(path: string): boolean {
        return this.dataGlobalService.checkPemisson(path);
    }

    getQueryParamRouter() {
        const queryParamMap = this.route.snapshot.queryParamMap;
        this.id = parseInt(queryParamMap.get('id') === null ? '' : queryParamMap.get('id'));
        this.role.id = parseInt(queryParamMap.get('id') === null ? '' : queryParamMap.get('id'));
        this.role.name = queryParamMap.get('name') === null ? '' : queryParamMap.get('name');
        this.role.description = queryParamMap.get('description') === null ? '' : queryParamMap.get('description');
    }

    getlistScreenSelected(): void {
        this.sub1 = this.permissionService.getlistScreenSelected(this.role.id).subscribe(
            repo => {
                this.listScreenSelected = (repo.screens as Screen[]);
                this.listScreenSelected.forEach(element => {
                    this.selects.push({
                        id: element.id,
                        name: element.display_name,
                    });
                });
                console.log(this.listScreenSelected);
                console.log(this.selects);
            },
            error => {
                this.dataGlobalService.actionFail(error.error);
            });
    }

    checkSelect(id: any): boolean {
        let check: boolean;
        for (let per of this.listScreenSelected) {
            if (per.id === id) {
                check = true;
            }
        }
        return check;
    }

    checkChange(): boolean {
        if (this.selects.length !== this.listScreenSelected.length) {
            return true;
        } else {
            let check = false;
            this.listScreenSelected.forEach(per => {
                let flag = 0;
                this.selects.forEach(select => {
                    if (select.id === per.id) {
                        flag = 1;
                        return;
                    }
                });
                if (flag === 0) {
                    check = true;
                    return;
                }
            });
            return check;
        }

    }

    deleteChanged(): void {
        let body = {
            screen_ids: [],
        }
        this.listScreenSelected.forEach(item => {
            body.screen_ids.push(item.id);
        });
        if (body.screen_ids.length > 0) {
            this.sub3 = this.permissionService.deletePerOfRole(this.role.id, body).subscribe(
                repo => {
                    this.updateData();
                    swal({
                        title: 'Thành công!',
                        text: 'Cập Các quyền thành công  ',
                        type: 'success',
                        confirmButtonText: 'OK'
                    });

                }, error => {
                    this.dataGlobalService.actionFail(error.error);
                });
        }

    }

    updateRuleRole(): void {
        swal({
            title: 'Xác nhận thay đổi',
            html: 'Dữ liệu trong nhóm quyền ' + this.role.name + ' sẽ được thay đổi!',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đồng ý',
            cancelButtonText: 'Hủy',
            showLoaderOnConfirm: true,
            allowOutsideClick: false,
            preConfirm: function () {
                return new Promise(resolve => {
                    setTimeout(function () {
                        resolve();
                    }, 500);
                });
            },
        }).then(categoryName => {
            if (categoryName) {
                //    CẬP NHẬT Ở ĐÂY
                this.saveAction();


            }
        }).catch(error => {
            this.dataGlobalService.actionFail(error.error);
        });
    }

    saveAction(): void {
        let body = {
            screen_ids: [],
        }
        this.selects.forEach(item => {
            body.screen_ids.push(item.id);
        });
        if (body.screen_ids.length > 0) {
            this.sub2 = this.permissionService.addPersForRole(body, this.role.id).subscribe(
                res => {
                    this.updateData();
                    swal({
                        title: 'Thành công!',
                        text: 'Cập nhật Các quyền thành công',
                        type: 'success',
                        confirmButtonText: 'OK'
                    });

                }, error => {
                    this.dataGlobalService.actionFail(error.error);
                });
        } else {
            this.deleteChanged();
        }

    }

    updateData(): void {
        this.listScreenSelected.length = 0;
        this.selects.length = 0;
        this.getlistScreenSelected();
    }

    getListCategory(): void {
        this.permissionService.getAllPermisson().then(repo => {
            this.listCategory = (repo as listPermissonRepo);
            for (let i = 0; i < this.listCategory.screen_categories.length; i++) {
                let show: Show = {
                    id: i + '.P',
                    name: 'ti-plus',
                    isShow: false,
                };
                this.listShow.push(show);
            }
        }).catch(error => {
            this.dataGlobalService.actionFail(error.error);
        });


    }

    clickItem(i: any, j: any): void {
        console.log((i + '.' + j));
        for (let is of this.listShow) {
            if (is.id === (i + '.' + j)) {
                is.isShow = !is.isShow;
                console.log(is.isShow);
                if (is.isShow) {
                    is.name = 'ti-minus';
                } else {
                    is.name = 'ti-plus';
                }
            }
        }

    }

    getClassName(i: any, j: any): string {
        let className = '';
        for (let is of this.listShow) {
            if (is.id === (i + '.' + j)) {
                className = is.name;
            }
        }
        return className;
    }

    getIsShow(i: any, j: any): boolean {
        let check: boolean;
        for (let is of this.listShow) {
            if (is.id === (i + '.' + j)) {
                check = is.isShow;
            }
        }
        return check;
    }


    selectItem(e: any, per: any): void {
        if (e.target.checked) {
            this.select = {
                id: per.id,
                name: per.display_name,
            };
            this.selects.push(this.select);
            console.log(this.select);
        } else {
            this.selects = this.selects.filter(item => item.id !== per.id);

        }
    }

    onBack(): void {
        this.ngOnDestroy();
        window.history.back();
    }
}


import { Component, OnInit, Input, OnDestroy } from "@angular/core";
import { FormBuilder, FormGroup, FormControl } from "@angular/forms";
import { log } from "util";
import { HttpClient } from "@angular/common/http";
import { AuthService } from "../../../services/authSevice";
import { Router } from '@angular/router';

import { ActivatedRoute, ParamMap } from '@angular/router';
import { Role } from "../../../models/api/response/RoleReponse";
import { RoleService } from "../../../services/role.service";
import { Subscription } from "rxjs/Subscription";
import { DataGlobalService } from "../../../services/data.global.service";

declare var swal: any;
@Component({
    selector: 'role-update-cmp',
    moduleId: module.id,
    templateUrl: 'role-update.component.html'
})

export class UpdateRoleComponent implements OnInit, OnDestroy {
    role: Role = new Role();
    id: number;
    form: FormGroup;
    subscription: Subscription;
    errorMsg = '';

    ngOnDestroy(): void {
        this.subscription !== undefined ? this.subscription.unsubscribe() : console.log(':D');
    }

    constructor(private router: Router,
        private route: ActivatedRoute,
        private dataGlobalService: DataGlobalService,
        private roleService: RoleService) {
    }

    ngOnInit() {
        this.getQueryParamRouter();
        if (this.checkPemision('/quan-tri/cap-nhat-vai-tro')) {
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
        this.role.name = queryParamMap.get('name') === null ? '' : queryParamMap.get('name');
        this.role.description = queryParamMap.get('description') === null ? '' : queryParamMap.get('description');
    }

    saveUpdate(): void {
        if (this.role.name.trim() !== '' && this.role.description.trim() !== '') {
            const body: any = {
                name: this.role.name,
                display_name: this.role.name,
                description: this.role.description
            };
            swal({
                title: 'Cập nhật nhóm quyền ?',
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
                        }, 1000);
                    });
                },
            }).then(r => {
                this.subscription = this.roleService.update(this.id, body).subscribe(
                    res => {
                        if (res.status = 'success') {
                            this.errorMsg = '';
                            setTimeout(function () {
                                swal({
                                    title: 'Thành công!',
                                    text: 'Bạn vừa cập nhật thông tin nhóm quyền !',
                                    type: 'success',
                                    confirmButtonText: 'Thoát'
                                }).then(isConfirm => {
                                    if (isConfirm) {
                                        window.history.back();
                                    }
                                });
                            }, 1000);
                        } else {
                            this.errorMsg = 'có lỗi bạn êy';
                        }
                        this.ngOnDestroy();
                    },
                    error => {
                        this.dataGlobalService.actionFail(error.error);
                        this.ngOnDestroy();
                    });

            });

        } else {
            this.errorMsg = 'Thông tin dữ liệu không thể để trống';
        }











    }

    onQuit(): void {
        this.ngOnDestroy();
        window.history.back();

    }







}
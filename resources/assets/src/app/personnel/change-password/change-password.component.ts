import { Component, OnInit, AfterViewInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators, AbstractControl, FormControl } from '@angular/forms';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { AuthService } from '../../services/authSevice';
import { PersonnelService } from '../../services/personnel.service';
import { Router, ActivatedRoute } from '@angular/router';
import { Employee, JobStatus, WorkingStatus, Department, Position, LateReason } from '../../models/api/response/ListEmployeesResponse';
import { DataGlobalService } from '../../services/data.global.service';
import { CommonValidator } from '../../validation/common.validator';

declare var swal: any;
declare var $: any;
import * as moment from 'moment';
import { HelperFunction } from '../../functions';
import { LoaderController } from '../../shared/loader/loader';

@Component({
    selector: 'change-password-cmp',
    moduleId: module.id,
    templateUrl: 'change-password.component.html'
})

export class ChangePasswordComponent implements OnInit {

    changePasswordForm: FormGroup

    loaderController: LoaderController = new LoaderController();

    constructor(private fb: FormBuilder,
        private http: HttpClient,
        private personnelService: PersonnelService,
        private dataGlobalService: DataGlobalService,
        private router: Router,
        private route: ActivatedRoute,
        private authService: AuthService) {
    }

    ngOnInit() {
        if (!this.dataGlobalService.checkPemisson('/danh-sach-nhan-su/sua-ho-so')) {
            this.router.navigate(['/trang-chu']);
        } else {
            this.loaderController.enableLoader();
            this.createForm();
            this.onChangeNewPassword();
            this.onChangeOldPassword();
            this.onChangeReNewPassword();
        }


    }

    goBack() {
        window.history.back();
    }

    createForm() {
        this.changePasswordForm = this.fb.group({
            old_password: ['', [Validators.required]],
            new_password: ['', [Validators.required, CommonValidator.isValidPassword]],
            re_new_password: ['', [Validators.required, CommonValidator.isValidPassword]],
        });

    }

    onChangeOldPassword(): void {
        this.changePasswordForm.get('old_password').valueChanges.subscribe(val => {
            if (val !== '' && val === this.changePasswordForm.get('new_password').value) {
                this.changePasswordForm.get('new_password').setErrors({ manualy: 'Mật khẩu mới không được trùng mật khẩu cũ' });
            }
            console.log(val);
        });
    }

    onChangeNewPassword(): void {
        this.changePasswordForm.get('new_password').valueChanges.subscribe(val => {
            if (val !== '' && val === this.changePasswordForm.get('old_password').value) {
                this.changePasswordForm.get('new_password').setErrors({ manualy: 'Mật khẩu mới không được trùng mật khẩu cũ' });
            }
        });
    }

    onChangeReNewPassword(): void {
        this.changePasswordForm.get('re_new_password').valueChanges.subscribe(val => {
            if (val !== '' && val !== this.changePasswordForm.get('new_password').value) {
                this.changePasswordForm.get('re_new_password').setErrors({ manualy: 'Nhập lại mật khẩu không chính xác' });
            }
        });
    }

    submit() {
        this.loaderController.pushLoader();
        const data: any = {
            old_password: this.changePasswordForm.get('old_password').value,
            new_password: this.changePasswordForm.get('new_password').value,
        };

        this.personnelService.changePassword(data).subscribe(
            () => {
                this.loaderController.releaseLoader();
                swal({
                    title: 'Thành công',
                    text: 'Đổi mật khẩu thành công'
                }).then((result) => {
                }).catch(swal.noop);
            },
            (error: HttpErrorResponse) => {
                this.loaderController.releaseLoader();
                if (error.headers.get('content-type') === 'application/json') {
                    if (error.error !== null) {
                        const err = JSON.parse(error.error).error;
                        Object.keys(err).forEach((key) => {
                            this.changePasswordForm.get(key).setErrors({ server: err[key] });
                        });
                    }
                }
                swal({
                    title: 'Đã có lỗi xảy ra',
                    text: error.error.message,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK',
                }).then((result) => {
                }).catch(swal.noop);
            }
        );
    }
}

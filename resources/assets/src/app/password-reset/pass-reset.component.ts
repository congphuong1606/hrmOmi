import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, FormControl, Validators} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../services/authSevice';
import * as constants from '../constants';
import {FcmserviceService} from '../services/fcm/fcmservice.service';
import {Router} from '@angular/router';
import {DataGlobalService} from '../services/data.global.service';
import {el} from '@angular/platform-browser/testing/src/browser_util';
import {CommonValidator} from '../validation/common.validator';

declare var swal: any;

@Component({
    selector: 'authenticate-cmp',
    moduleId: module.id,
    templateUrl: 'pass-reset.component.html'
})


export class PassResetComponent implements OnInit {
    formEmailPassReset: FormGroup;
    formPasswordPassReset: FormGroup;
    test: Date = new Date();
    isFromInputEmail = true;
    passError = '';
    confirmPassError = '';
    codeError = '';
    email = '';
    successfully = false;

    ngOnInit() {

    }

    constructor(private fb: FormBuilder,
                private http: HttpClient,
                private router: Router,
                private dataGlobalService: DataGlobalService,
                private fcmService: FcmserviceService,
                private authService: AuthService) {


        this.formEmailPassReset = this.fb.group({
            email: ['', Validators.compose([Validators.required, Validators.email])],
        });
    }

    sendEmailPassReset() {
        this.authService.sendPasswordResetEmail(this.formEmailPassReset.value).then(
            response => {
                this.email = this.formEmailPassReset.value.email;
                this.isFromInputEmail = false;
                this.formPasswordPassReset = this.fb.group({
                    pass: ['', [Validators.required, CommonValidator.isValidPassword]],
                    confirmPass: ['', [Validators.required, CommonValidator.isValidPassword]],
                    code: '',
                });
                // alert('Hãy check mã code được gửi đến mail của bạn');

            }).catch(error => {
                this.dataGlobalService.actionFail(error.error);
            }
        );

    }

    gotoDemoComponent() {
        this.router.navigate(['/demo']);
    }

    focusInput(number: number) {
        switch (number) {
            case 1:
                this.passError = '';
                break;
            case 2:
                this.confirmPassError = '';
                break;
            case 3:
                this.codeError = '';
                break;

        }
    }

    goToResetPass() {
        this.router.navigate(['/password_reset']);
    }


    resetPass() {
        const pass = this.formPasswordPassReset.get('pass').value;
        const confirmPass = this.formPasswordPassReset.get('confirmPass').value;
        const code = this.formPasswordPassReset.get('code').value;
        if (confirmPass !== pass && pass !== '') {
            this.confirmPassError = 'Password does not match the confirm password!';
        } else {
            this.authService.resetPass(
                {
                    email: this.email,
                    password: pass,
                    verified_code: code
                }).then(
                response => {
                    if (response.json().status === 'success') {
                        this.successfully = true;
                    }


                }).catch(error => {
                    if (error.json().status === 'fail') {
                        this.codeError = 'Verify code invalid!';
                    } else {
                        this.dataGlobalService.actionFail(error.error);
                    }

                }
            );
        }

    }

    focusOutReset(number: number) {
        const pass = this.formPasswordPassReset.get('pass');
        const confirmPass = this.formPasswordPassReset.get('confirmPass');
        const code = this.formPasswordPassReset.get('code');
        switch (number) {
            case 1:
                if (pass.hasError('required')) {
                    // this.passError = 'Password not null!';
                } else {
                    if (pass.hasError('isValidPassword')) {
                        this.passError = 'Must contain at least one number and one uppercase and lowercase letter, ' +
                            'and at least 8 or more characters!';
                    }
                }
                break;
            case 2:

                if (confirmPass.hasError('required')) {
                    // this.confirmPassError = 'Password not null!';
                } else {
                    if (confirmPass.hasError('isValidPassword')) {
                        this.confirmPassError = 'Must contain at least one number and one uppercase and lowercase letter, ' +
                            'and at least 8 or more characters!';
                    } else {
                        if (confirmPass.value !== pass.value && pass.value !== '') {
                            this.confirmPassError = 'Password does not match the confirm password!';
                        }
                    }
                }
                break;
            case 3:
                if (code.value === '') {
                    this.codeError = 'Verify code invalid!';
                }

                break;

        }
    }

    validateForm(): boolean {
        const pass = this.formPasswordPassReset.get('pass').value;
        const confirmPass = this.formPasswordPassReset.get('confirmPass').value;
        const code = this.formPasswordPassReset.get('code').value;
        if (pass !== '' && pass === confirmPass && code !== ''
            && this.passError === '' &&
            this.confirmPassError === '' && this.codeError === '') {
            return this.formPasswordPassReset.invalid;
        } else {
            return true;
        }

    }

    returnLogin() {
        this.router.navigate(['/login']);
    }
}


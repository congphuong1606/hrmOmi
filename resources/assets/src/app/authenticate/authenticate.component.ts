import {Component, OnInit} from '@angular/core';
import {FormBuilder, FormGroup, FormControl} from '@angular/forms';
import {log} from 'util';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../services/authSevice';
import * as constants from '../constants';
import {FcmserviceService} from '../services/fcm/fcmservice.service';
import {Router} from '@angular/router';

@Component({
    selector: 'authenticate-cmp',
    moduleId: module.id,
    templateUrl: 'authenticate.component.html'
})

export class AuthenticateComponent implements OnInit {
    form: FormGroup;
    textLoginFail = '';
    test : Date = new Date();

    ngOnInit() {
        localStorage.removeItem(constants.URLS_NAME);
        if (localStorage.getItem(constants.REMEMBER_LOGIN) !== undefined && localStorage.getItem(constants.REMEMBER_LOGIN) === 'logined') {
            this.router.navigate(['/']);
        }
    }

    constructor(private fb: FormBuilder,
                private http: HttpClient,
                private router: Router,
                private fcmService: FcmserviceService,
                private authService: AuthService) {
        this.form = this.fb.group({
            email: '',
            password: ''
        });
    }

    login() {
        this.authService.login(this.form.value).then(response => {
            this.authService.setToken(response.json().token);
            this.router.navigate(['/']);
        }).catch(a => {
            this.textLoginFail = 'Email hoặc mật khẩu không đúng!';
            }
        );

    }

    gotoDemoComponent() {
        this.router.navigate(['/demo']);
    }

    focusInput() {
        this.textLoginFail = '';
    }

    goToResetPass() {
        this.router.navigate(['/password_reset']);
    }
}


import {Injectable} from '@angular/core';
import 'rxjs/add/operator/toPromise';
import {HttpClient} from '@angular/common/http';
import {AuthService} from './authSevice';
import * as constants from '../constants';
import {TimeCongig} from '../models/api/response/SettingReponse';
import * as moment from 'moment';

declare var swal: any;
declare var $: any;

declare interface UrlsReponse {
    status: string;
    message: string;
    urls: string[];
}

export const TOKEN_NAME = 'jwt_token';
export const URLS_NAME = 'jwt_urls';


@Injectable()
export class DataGlobalService {
    public saveOverTimeloading = false;
    public isHomePage = false;
    urlsReponse: UrlsReponse = {
        status: '',
        message: '',
        urls: []
    }
    datalistEmail: any[] = [];
    isCallApi = false;

    constructor(public http: HttpClient, public auth: AuthService) {

    }

    getTimeConfig(): any {
        return (JSON.parse(localStorage.getItem(constants.TIME_CONFIG)) as TimeCongig);
    }

    disBtnSubmit() {
        this.saveOverTimeloading = true;
    }

    btnIsloading(): boolean {
        return this.saveOverTimeloading;
    }

    enableBtnSubmit() {
        setTimeout(k => {
            this.saveOverTimeloading = false;
        }, 1000);
    }

    getSettingValues() {
        const url = 'api/setting';
        return this.http.get<any>(url);
    }

    getEmployId(): number {
        return JSON.parse(localStorage.getItem(constants.USER_INFO)).employee_id;
    }

    getAvatar(): number {
        return JSON.parse(localStorage.getItem(constants.USER_INFO)).avatar;
    }

    getCurentEmail(): string {
        return (JSON.parse(localStorage.getItem(constants.USER_INFO)).email);
    }

    isAdmin(): boolean {
        return JSON.parse(localStorage.getItem(constants.USER_INFO)).is_admin;
    }

    isAdvancedAdmin(): boolean {
        return JSON.parse(localStorage.getItem(constants.USER_INFO)).email === 'admin@ominext.com';
    }

    getuserId(): number {
        return JSON.parse(localStorage.getItem(constants.USER_INFO)).id;
    }

    checkPemisson(path: string): boolean {
        const url = (JSON.parse(localStorage.getItem(URLS_NAME)) as string[]).find(
            url_Path => {
                return url_Path === path;
            }
        );
        return url === undefined ? false : true;
    }

    getPermisionUrls(idUser: any) {
        const url = 'api/users/' + idUser + '/urls';
        return this.http.get<UrlsReponse>(url);
    }

    getUrlsLocal(): string {
        return localStorage.getItem(URLS_NAME);
    }

    setUrlsLocal(data: string[]): void {
        localStorage.setItem(URLS_NAME, JSON.stringify(data));
    }

    getInfoUser() {
        return this.http.get<any>('api/user-info');
    }

    logOut() {
        return this.http.post<any>('api/auth/logout', {
            token: this.auth.getToken(),
            device_id: parseInt(localStorage.getItem(constants.DEVICE_ID))
        });
    }

    log(msg: any): void {
        console.log(msg);
    }

    actionFail(error): void {
        const msg = (error !== null && error !== undefined && error !== '') ? JSON.parse(error).message + '!' : 'Đã xảy ra lỗi!';
        if (msg !== 'token_invalid!' && msg !== 'token_expired!') {
            swal({title: 'Vui lòng kiểm tra lại', text: msg, type: 'error'}).then(ok => {
            }, cancel => {
            });
        }
    }

    intervalTimeInDay(startTime: string, endTime: string): number {
        if (startTime === undefined || startTime === null || startTime === '' ||
            endTime === undefined || endTime === null || endTime === ''
        ) {
            return 0;
        } else {
            const time1 = moment(startTime, 'HH:mm');
            const time2 = moment(endTime, 'HH:mm');
            const duration = moment.duration(time2.diff(time1));
            return duration.asMinutes();
        }

    }

    intervalTimeInDays(startTime: string, endTime: string): number {
        if (startTime === undefined || startTime === null || startTime === '' ||
            endTime === undefined || endTime === null || endTime === ''
        ) {
            return 0;
        } else {
            const time1 = moment(startTime, 'DD-MM-YYYY HH:mm');
            const time2 = moment(endTime, 'DD-MM-YYYY HH:mm');
            const duration = moment.duration(time2.diff(time1));
            return duration.asMinutes();
        }
    }

    initInputDateTimePicker(idElement: string, listDayDisable: number[]) {
        $('#' + idElement).datetimepicker({
            format: 'DD-MM-YYYY HH:mm',
            useCurrent: false,
            ignoreReadonly: true,
            sideBySide: true,
            daysOfWeekDisabled: listDayDisable,
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
        }).attr('readonly', 'readonly');
    }

    initInputDatePicker(idElement: string, listDayDisable: number[]) {
        $('#' + idElement).datetimepicker({
            format: 'DD-MM-YYYY',
            useCurrent: false,
            ignoreReadonly: true,
            daysOfWeekDisabled: listDayDisable,
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
        }).attr('readonly', 'readonly');
    }

    initInputTimePicker(idElement: string) {
        $('#' + idElement).datetimepicker({
            format: 'HH:mm',
            ignoreReadonly: true,
            icons: {
                time: 'fa fa-clock-o',
                date: 'fa fa-calendar',
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right',
                today: 'fa fa-screenshot',
                clear: 'fa fa-trash',
                close: 'fa fa-remove'
            }
        }).attr('readonly', 'readonly');
    }


    setLocation(isHomePage: boolean) {
        this.isHomePage = isHomePage;
    }

    checkEmailFocus(approver: string) {
        const url = 'api/users/check/email?email=' + approver;
        return this.http.get<any>(url);
    }

    convertDateTIme(time: string): string {
        const ddmmyyyy = time.split(' ')[0];
        const hhmm = time.split(' ')[1];
        const date = ddmmyyyy.split('-')[2] + '-' + ddmmyyyy.split('-')[1] + '-' + ddmmyyyy.split('-')[0]
        return date + ' ' + hhmm.split(':')[0] + ':' + hhmm.split(':')[1];
    }

    convertDate(time: string): string {
        const date = time.split(' ')[0];
        // console.log(date.split('-')[2] + '-' + date.split('-')[1] + '-' + date.split('-')[0])
        return date.split('-')[2] + '-' + date.split('-')[1] + '-' + date.split('-')[0];
    }

    searchUserWithEmail(value: string) {
        const url = 'api/users/email/find?email=' + value;
        return this.http.get<any>(url);
    }


    searchEmail(value: string) {
        if (value.length >= 3) {
            if (!this.isCallApi) {
                this.isCallApi = true;
                const url = 'api/users/email/find?email=' + value;
                return this.http.get<any>(url).subscribe(
                    repo => {
                        this.datalistEmail = repo.users as any[];
                        setTimeout(k => {
                            this.isCallApi = false;
                        }, 500);
                    },
                    error => {
                        setTimeout(k => {
                            this.isCallApi = false;
                        }, 500);
                        console.log(error.message);
                    }
                );
            }
        } else {
            this.datalistEmail.length = 0;
        }

    }


    calculateOTNumber(start: string, end: string): string {
        const day1 = moment(start, 'YYYY-MM-DD');
        const day2 = moment(end, 'YYYY-MM-DD');
        const duration = moment.duration(day2.diff(day1));
        const numberDay = duration.asDays();
        let time = 0;
        if (numberDay === 0) {
            time = this.categoryTime(start.split(' ')[1], end.split(' ')[1]);
        }
        if (numberDay > 0) {

        }
        return time + '';
    }


    private categoryTime(start: string, end: string) {
        const time1 = moment(start, 'HH:mm');
        const time2 = moment(end, 'HH:mm');

        return 0;
    }

}

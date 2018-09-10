import {Injectable} from '@angular/core';
import 'rxjs/add/operator/toPromise';
import {HttpClient} from '@angular/common/http';
import {AuthService} from './authSevice';

@Injectable()
export class HomeService {

    constructor(public http: HttpClient, public auth: AuthService) {
    }

    getNotifications(index: any) {
        return this.http.get<any>('api/notifications?page=' + index);
    }

    getBirthdayInWeek() {
        return this.http.get<any>('api/employees/in_month/birthday');
    }

    getHomepageInfo() {
        return this.http.get<any>('api/employees/homepage');
    }

    updateNews(id: any) {
        console.log('read')
        return this.http.get<any>('api/notifications/' + id + '/mark_as_read');
    }
}


import {Injectable} from '@angular/core';
import 'rxjs/add/operator/toPromise';
import {HttpClient} from '@angular/common/http';
import {AuthService} from './authSevice';

@Injectable()
export class SettingService {
    private baseUrl = 'api/setting';

    constructor(public http: HttpClient, public auth: AuthService) {
    }

    get() {
        return this.http.get<any>(this.baseUrl);
    }

    update(body: any) {
        return this.http.put<any>(this.baseUrl, body);
    }

}


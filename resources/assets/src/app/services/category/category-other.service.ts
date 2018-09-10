import {Injectable} from '@angular/core';
import 'rxjs/add/operator/toPromise';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../authSevice';

export const TOKEN_NAME = 'jwt_token';

@Injectable()
export class CategoryOtherService {
    baseUrl = 'api/other_categories';

    constructor(public http: HttpClient, public auth: AuthService) {
    }

    getList(type: string) {
        const url = this.baseUrl + '?type=' + type;
        return this.http.get<any>(url);
    }

    create(body: any) {
        const url = this.baseUrl;
        return this.http.post<any>(url, body);
    }

    get(id: number) {
        const url = this.baseUrl + '/' + id;
        return this.http.get<any>(url);
    }

    delete(id: number) {
        const url = this.baseUrl + '/' + id;
        return this.http.delete<any>(url);
    }

    update(id: number, body: any) {
        const url = this.baseUrl + '/' + id;
        return this.http.put<any>(url, body);
    }


    createReasonCategory(data) {
        const url = 'api/late_reasons';
        return this.http.post<any>(url, data);
    }

    getListReasonsCategory() {
        const url = 'api/late_reasons';
        return this.http.get<any>(url);
    }

    deleteReasonCateGory(id: any) {
        const url = 'api/late_reasons/' + id;
        return this.http.delete<any>(url);
    }

    updateReasonCategory(id: number, body) {
        const url = 'api/late_reasons/' + id;
        return this.http.put<any>(url, body);
    }
}

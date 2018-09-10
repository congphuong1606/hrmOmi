
import { Injectable } from '@angular/core';
import 'rxjs/add/operator/toPromise';
import { HttpClient } from '@angular/common/http';
import { AuthService } from '../authSevice';
@Injectable()
export class CategoryService {
    baseUrl = 'api/';
    constructor(public http: HttpClient, public auth: AuthService) {
    }
    getList(type: string) {
        const url = this.baseUrl + '' + type;
        return this.http.get<any>(url);
    }

    create(type: string, body: any) {
        const url = this.baseUrl + '' + type;
        return this.http.post<any>(url, body);
    }

    get(type: string, id: number) {
        const url = this.baseUrl + '' + type + '/' + id;
        return this.http.get<any>(url);
    }

    delete(type: string, id: number) {
        const url = this.baseUrl + '' + type + '/' + id;
        return this.http.delete<any>(url);
    }

    update(type: string, id: number, body: any) {
        const url = this.baseUrl + '' + type + '/' + id;
        return this.http.put<any>(url, body);
    }


}

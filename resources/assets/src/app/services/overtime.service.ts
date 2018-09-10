import {Injectable} from '@angular/core';
import 'rxjs/add/operator/toPromise';
import {HttpClient} from '@angular/common/http';
import {AuthService} from './authSevice';

@Injectable()
export class OvertimeService {
    private urlRoot = 'api/timesheet/over_times';

    constructor(public http: HttpClient, public auth: AuthService) {
    }

    getList(curentPage: number, request: any) {
        const url = 'api/timesheet/over_times?page=' + curentPage + '&&limit=15'
            + '&&project_category_id=' + request.project_category_id +
            '&&month=' + request.month + '&&year=' + request.year + '&&approved=' + request.approved
            + '&&search_data=' + request.search_data;
        return this.http.get<any>(url);
    }

    getListForCurrentUser(page: number) {
        const limit = 10;
        const url = 'api/timesheet/over_times?own=1&&page=' + page + '&&limit=' + limit;
        console.log('CALL API: DANH SACH OVERTIME ');
        return this.http.get<any>(url);
    }

    create(body: any) {
        return this.http.post<any>(this.urlRoot, body);
    }

    get(id: any) {
        return this.http.get<any>(this.urlRoot + '/' + id);
    }

    delete(id: any) {
        return this.http.delete<any>(this.urlRoot + '/' + id);
    }


    update(body: any, id: any) {
        return this.http.put<any>(this.urlRoot + '/' + id, body);
    }

    getListOverApprover(idUser: number, page: number, request: string) {
        const limit = 10;
        const url = this.urlRoot + '/approver/approve?page=' + page + '&&limit=' + limit + request;
        return this.http.get<any>(url);
    }

    approveOvertime(body: any) {
        const url = 'api/timesheet/over_times/approve';
        return this.http.post<any>(url, body);
    }


    refuseOvertime(body) {
        const url = 'api/timesheet/over_times/refuse';
        return this.http.post<any>(url, body);
    }


}


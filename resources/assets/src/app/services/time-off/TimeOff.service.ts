import {Injectable} from '@angular/core';
import 'rxjs/add/operator/toPromise';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../authSevice';
import {ListTimeOffRepo, ListTimeOffApproverRepo, ListDayOffRepo} from '../../models/api/response/TimeOffReponse';
import {forEach} from '@angular/router/src/utils/collection';


export const TOKEN_NAME = 'jwt_token';

@Injectable()
export class TimeOffService {
    constructor(public http: HttpClient, public auth: AuthService) {
    }


    // TẠO MỚI TIME OFF
    create(body: any) {
        return this.http.post<any>('api/timesheet/time_off', body);

    }

    // get toàn bộ đơn đăng ký đi muộn/ ra ngoài/ về sớm của một người
    getListTimeOff(page: number) {
        const limit = 10;
        const url = 'api/employees/time_off/own?action=0&&page=' + page + '&&limit=' + limit;
        return this.http.get<ListTimeOffRepo>(url);
    }

    // get toàn bộ đơn đăng ký nghỉ phép của một người
    getListDayOff(page: number) {
        const limit = 10;
        const url = 'api/employees/time_off/own?action=1&&page=' + page + '&&limit=' + limit;
        return this.http.get<ListDayOffRepo>(url);
    }

    // get đơn xin nghỉ phép cho người kiểm duyệt
    getListTimeOffApprover(idUser: number, page: number, requestPram: string) {
        const limit = 15;
        const url = 'api/timesheet/time_off/approver/approve?page=' + page + '&&limit=' + limit + requestPram;
        return this.http.get<ListTimeOffApproverRepo>(url);
    }

    getAllTimeOff(page: number, request: any) {
        let status = '';
        request.status.forEach(item => {
            status = status + '&&status[]=' + item;
        });
        const limit = 15;
        const url = 'api/timesheet/time_off?page='
            + page + '&&limit=' + limit
            + '&&month=' + request.month
            + '&&year=' + request.year
            + '&&approved=' + request.approved
            + '&&search_data=' + request.search_data + status;
        return this.http.get<ListTimeOffApproverRepo>(url);
    }

    // đồng ý đơn kiểm duyệt
    approveTimeOff(body: any) {
        const limit = 10;
        const url = 'api/timesheet/time_off/approve';
        console.log('CALL API:  DUYET ');
        return this.http.post<any>(url, body);
    }

    // từ chối đơn kiểm duyệt
    refuseTimeOff(body) {
        const limit = 10;
        const url = 'api/timesheet/time_off/refuse';
        console.log('CALL API: TU CHOI ');
        return this.http.post<any>(url, body);
    }

    delete(id: any) {
        const url = 'api/timesheet/time_off/' + id;
        return this.http.delete<any>(url);
    }

    update(body: any, id: any) {
        const url = 'api/timesheet/time_off/' + id;
        return this.http.put<any>(url, body);
    }


    importFileTimeOff(body: { file: any }) {
        const url = 'api/timesheet/time_off_excel_files';
        const formData = new FormData();
        formData.append('file', body.file);
        return this.http.post<any>(url, formData);
    }

    getListImportHistory() {
        const url = 'api/timesheet/time_off_excel_files?limit=5&&page=1';
        return this.http.get<any>(url);
    }

    deleteDataFileExel(id: number) {
        const url = 'api/timesheet/time_off_excel_files/' + id;
        return this.http.delete<any>(url);
    }


    showAnTimeOff(id: string) {
        const url = 'api/timesheet/time_off/' + id;
        return this.http.get<any>(url);
    }

    getListApproverEmail() {
        const url = 'api/employees/time_off/remaining';
        return this.http.get<any>(url);
    }
}


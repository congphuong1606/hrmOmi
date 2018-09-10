import {Injectable} from '@angular/core';
import 'rxjs/add/operator/toPromise';
import {HttpClient} from '@angular/common/http';
import {OneRole} from '../models/api/response/RoleReponse';
import {AuthService} from './authSevice';


@Injectable()
export class RoleService {

    constructor(public http: HttpClient, public auth: AuthService) {
    }

    getListUserWithRoleID(roleId: Number, isInverse: number, page: any, search_data: any, per_page: any) {
        const url = 'api/roles/' + roleId + '/users?inverse=' + isInverse +
            '&limit=' + per_page + '&page=' + page + '&search_data=' + search_data;
        return this.http.get<any>(url);
    }

    addRoleForUser(userIds: number[], idRole: number) {
        const url = 'api/roles/' + idRole + '/attach/users';
        return this.http.post<any>(url, {user_ids: userIds});
    }

    deleteRoleUser(idRole: number, idUser: number) {
        const url = 'api/roles/' + idRole + '/detach/users';
        return this.http.post<any>(url, {user_ids: [idUser]});
    }


    getListRole() {
        const url = 'api/roles';
        return this.http.get<any>(url);
    }


    createRole(data: any) {
        const url = 'api/roles';
        return this.http.post<any>(url, data);
    }

    getRole(id: number) {
        const url = 'api/roles' + id;
        return this.http.get<OneRole>(url);
    }

    deleteRole(id: number) {
        return this.http.delete<any>('api/roles/' + id);
    }

    update(id: number, body: any) {
        return this.http.put<any>('api/roles/' + id, body);
    }

}


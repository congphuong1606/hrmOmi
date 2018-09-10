import {Injectable} from '@angular/core';
import 'rxjs/add/operator/toPromise';
import {HttpClient} from '@angular/common/http';
import {AuthService} from '../authSevice';

export const TOKEN_NAME = 'jwt_token';

@Injectable()
export class JobStatusService {
    constructor(public http: HttpClient, public auth: AuthService) {
    }

    getList(): Promise<any> {
        return this.http.get('api/job_status')
            .toPromise()
            .then(response => response)
            .catch(this.handleError);
    }

    create(body: any): Promise<any> {
        return this.http
            .post('api/job_status', body)
            .toPromise()
            .then(res => res)
            .catch(this.handleError);
    }


    get(id: number): Promise<any> {
        const url = 'api/job_status/' + id;
        return this.http.get(url)
            .toPromise()
            .then(res => res)
            .catch(this.handleError);
    }

    delete(id: number): Promise<any> {
        return this.http.delete('api/job_status/' + id)
            .toPromise()
            .then(res => res)
            .catch(this.handleError);
    }

    update(id: number, body: any): Promise<any> {
        const url = 'api/job_status/' + id;
        return this.http
            .put(url, body)
            .toPromise()
            .then(res => res)
            .catch(this.handleError);
    }

    private handleError(error: any): Promise<any> {
        console.error('An error occurred', error);
        return Promise.reject(error.message || error);
    }
}

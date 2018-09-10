import {Injectable} from '@angular/core';
import {
    HttpRequest,
    HttpHandler,
    HttpEvent,
    HttpInterceptor, HttpResponse
} from '@angular/common/http';
import {AuthService} from './authSevice';
import {Observable} from 'rxjs/Observable';
import {finalize, tap} from 'rxjs/operators';
import {Router} from '@angular/router';

declare var swal: any;

@Injectable()
export class TokenInterceptor implements HttpInterceptor {

    constructor(public auth: AuthService, private router: Router) {
    }

    intercept(request: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {
        request = request.clone({
            setHeaders: {
                Authorization: 'Bearer' + this.auth.getToken(),
                Accept: 'application/json',
            }
        });
        return next.handle(request).pipe(
            tap(
                event => {
                },
                error => {
                    if (error.status === 401) {
                        this.router.navigate(['/login']);
                        localStorage.clear();
                    }
                }
            ),
            finalize(() => {

            })
        );
    }

}
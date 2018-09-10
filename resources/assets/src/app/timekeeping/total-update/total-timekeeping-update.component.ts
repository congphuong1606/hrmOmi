import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, FormControl} from "@angular/forms";
import {log} from "util";
import {HttpClient} from "@angular/common/http";
import {AuthService} from "../../services/authSevice";
import { Router, NavigationEnd } from '@angular/router';
import swal from 'sweetalert2';

@Component({
    selector: 'total-timekeeping-update-cmp',
    moduleId: module.id,
    templateUrl: 'total-timekeeping-update.component.html'
})

export class TotalTimekeepingUpdateComponent implements OnInit {
    form: FormGroup

    ngOnInit() {

    }

    constructor(private fb: FormBuilder,
                private http:HttpClient,
                private authService: AuthService,
                private router: Router) {
        this.form = this.fb.group({
            email: '',
            password: ''
        });
    }

    login() {
        return this.authService.login(this.form.value);
    }

    goToPersonnelCreate() {
        this.router.navigate(['/danh-sach-nhan-su/them-nhan-su']);
    }

    deletePersonnel() {
        swal({
            title: 'Xác nhận',
            text: "Bạn có muốn xóa bản ghi này",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Có',
            cancelButtonText: 'Không'
          }).then((result) => {
            if (result.value) {
              swal(
                'Deleted!',
                'Your file has been deleted.',
                'success'
              )
            }
          })
    }
}

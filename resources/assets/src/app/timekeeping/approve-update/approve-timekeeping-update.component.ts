import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, FormControl} from "@angular/forms";
import {log} from "util";
import {HttpClient} from "@angular/common/http";
import {AuthService} from "../../services/authSevice";
import { Router, NavigationEnd } from '@angular/router';
import swal from 'sweetalert2';

@Component({
    selector: 'approve-timekeeping-update-cmp',
    moduleId: module.id,
    templateUrl: 'approve-timekeeping-update.component.html'
})

export class ApproveTimekeepingUpdateComponent implements OnInit {
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
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
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

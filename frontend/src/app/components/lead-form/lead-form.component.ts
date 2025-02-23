import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { LeadService } from '../../services/lead.service';
import { MatSnackBar } from '@angular/material/snack-bar';
@Component({
  selector: 'app-lead-form',
  templateUrl: './lead-form.component.html',
  styleUrls: ['./lead-form.component.scss']
})
export class LeadFormComponent {
  leadForm: FormGroup;
  sources = ['Facebook', 'Google', 'Linkedin', 'Manual'];

  constructor(
    private fb: FormBuilder,
    private leadService: LeadService,
    private snackBar: MatSnackBar
  ) {
    this.leadForm = this.fb.group({
      name: ['', [Validators.required]],
      email: ['', [Validators.required, Validators.email]],
      source: ['', [Validators.required]]
    });
  }

  onSubmit(): void {
    if (this.leadForm.valid) {
      const leadPayload = this.leadForm.value;
      leadPayload.source = leadPayload.source.toLowerCase();
      this.leadService.createLead(leadPayload).subscribe({
        next: (response) => {
          this.snackBar.open('Lead created successfully!', 'Close', {
            duration: 3000,
            panelClass: ['success-snackbar']
          });
          this.leadForm.reset();
          // Reset validation states
          Object.keys(this.leadForm.controls).forEach(key => {
            const control = this.leadForm.get(key);
            control?.setErrors(null);
            control?.markAsUntouched();
          });
          this.leadService.triggerRefreshLeads();
        },
        error: (error) => {
          console.error('Error creating lead:', error);

          if (error.error?.message) {
            let message = error.error?.message;
            if (error.error?.data) {
              Object.keys(error.error.data).forEach(fieldName => {
                const control = this.leadForm.get(fieldName);
                if (control) {
                  control.setErrors(error.error.data[fieldName]);
                }
                message += `\n(${fieldName}): ${error.error.data[fieldName]}`;
              });
            }
            this.snackBar.open(message, 'Close', {
              duration: 3000,
              panelClass: ['error-snackbar']
            });
          } else {
            this.snackBar.open('Error creating lead. Please try again.', 'Close', {
              duration: 3000,
              panelClass: ['error-snackbar']
            });
          }
        }
      });
    }
  }
} 
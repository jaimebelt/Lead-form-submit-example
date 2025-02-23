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
        next: () => {
          this.leadForm.reset();
          
          this.snackBar.open('Lead created successfully', 'Close', {
            duration: 3000,
            panelClass: ['success-snackbar']
          });
        },
        error: (error) => {
          console.error('Error creating lead:', error);
          
          this.snackBar.open('Error creating lead', 'Close', {
            duration: 3000,
            panelClass: ['error-snackbar']
          });
        }
      });
    }
  }
} 
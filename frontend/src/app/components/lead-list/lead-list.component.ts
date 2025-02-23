import { Component, OnInit, OnDestroy } from '@angular/core';
import { LeadService } from '../../services/lead.service';
import { Lead } from '../../models/lead.interface';
import { MatSnackBar } from '@angular/material/snack-bar';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-lead-list',
  templateUrl: './lead-list.component.html',
  styleUrls: ['./lead-list.component.scss']
})
export class LeadListComponent implements OnInit, OnDestroy {
  leads: Lead[] = [];
  displayedColumns: string[] = ['lead_id', 'name', 'email', 'source'];
  private refreshSubscription: Subscription;

  constructor(
    private leadService: LeadService,
    private snackBar: MatSnackBar
  ) {
    this.refreshSubscription = this.leadService.refreshLeads$.subscribe(() => {
      this.loadLeads();
    });
  }

  ngOnInit(): void {
    this.loadLeads();
  }

  ngOnDestroy() {
    if (this.refreshSubscription) {
      this.refreshSubscription.unsubscribe();
    }
  }

  loadLeads(): void {
    this.leadService.getLeads().subscribe({
      next: (leads) => {
        this.leads = leads.data;
      },
      error: (error) => {
        console.error('Error loading leads:', error);
        this.snackBar.open('Error loading leads', 'Close', {
          duration: 3000,
          panelClass: ['error-snackbar']
        });
      }
    });
  }
}
from datetime import datetime, timedelta
from django.db import models
import uuid
import os
from django.utils import timezone


class Auction(models.Model):
    name = models.CharField(max_length=255)

    def __str__(self):
        return self.name


class Warehouse(models.Model):
    name = models.CharField(max_length=255)

    def __str__(self):
        return self.name


class Company(models.Model):
    name = models.CharField(max_length=255)

    class Meta:
        verbose_name_plural = "Companies WH"

    def __str__(self):
        return self.name


class Customer(models.Model):
    name = models.CharField(max_length=255)

    def __str__(self):
        return self.name


class Account(models.Model):
    name = models.CharField(max_length=255)
    customer = models.ForeignKey(Customer, on_delete=models.CASCADE, related_name='accounts')

    def __str__(self):
        return self.name


def unique_upload_path(instance, filename):
    base, extension = os.path.splitext(filename)
    unique_filename = uuid.uuid4().hex
    field_name = ''  # Здесь будет имя поля
    for field in instance._meta.fields:
        if getattr(instance, field.name) == instance:
            field_name = field.name
            break
    return f'uploads/{field_name}/{unique_filename}{extension}'


class Lot(models.Model):
    STATUS_CHOICES = [
        ('new', 'New'),
        ('dispatched', 'Dispatched'),
        ('terminal', 'Terminal'),
        ('loading', 'Loading'),
        ('shipped', 'Shipped'),
        ('unloaded', 'Unloaded'),
        ('archived', 'Archived'),
    ]

    bos = models.FileField(upload_to=unique_upload_path, blank=True, db_index=True)
    photo_a = models.ImageField(upload_to=unique_upload_path, blank=True)
    photo_d = models.ImageField(upload_to=unique_upload_path, blank=True)
    photo_w = models.ImageField(upload_to=unique_upload_path, blank=True)
    video = models.FileField(upload_to=unique_upload_path, blank=True)
    title = models.FileField(upload_to=unique_upload_path, blank=True)
    photo_l = models.ImageField(upload_to=unique_upload_path, blank=True)

    STATUS_ORDER = [status[0] for status in STATUS_CHOICES]
    status_changed = models.DateTimeField(auto_now_add=True)
    status = models.CharField(max_length=10, choices=STATUS_CHOICES, default='new')
    date_purchase = models.DateField(blank=False)
    date_warehouse = models.DateField(null=True, blank=True)
    payment_date = models.DateField(null=True, blank=True)
    date_booking = models.DateField(null=True, blank=True)
    data_container = models.DateField(null=True, blank=True)
    date_unloaded = models.DateField(null=True, blank=True)
    auto = models.CharField(max_length=255, blank=False, db_index=True)
    vin = models.CharField(max_length=17, unique=True, blank=False, db_index=True)
    lot = models.CharField(max_length=255, unique=True, blank=False, db_index=True)
    account = models.ForeignKey(Account, on_delete=models.CASCADE)
    auction = models.ForeignKey(Auction, on_delete=models.CASCADE)
    url = models.URLField(max_length=200, blank=False)
    customer = models.ForeignKey(Customer, on_delete=models.CASCADE, blank=False, db_index=True)
    warehouse = models.ForeignKey(Warehouse, on_delete=models.CASCADE, blank=True, null=True)
    company = models.ForeignKey(Company, on_delete=models.CASCADE)
    price = models.DecimalField(max_digits=10, decimal_places=2, blank=False)
    keys = models.BooleanField()
    line = models.CharField(max_length=255)
    booking_number = models.CharField(max_length=255)
    container = models.CharField(max_length=255)
    ata_data = models.DateField(null=True, blank=True)

    def __str__(self):
        return f"{self.auto} - {self.vin}, {self.lot}. "

# class LotIterator:
#     def __init__(self, lots):
#         self._lots = lots
#         self._index = 0
#
#     def __iter__(self):
#         return self
#
#     def __next__(self):
#         if self._index < len(self._lots):
#             result = self._lots[self._index]
#             self._index += 1
#             return result
#         raise StopIteration

    def update_status(self, new_status):
        self.status = new_status
        self.status_changed = timezone.now()
        self.save()

    def move_to_next_status(self):
        if self.status == 'new' and self.payment_date is not None:
            self.update_status('dispatched')
        elif self.status == 'dispatched' and self.date_warehouse is not None:
            self.update_status('terminal')
        elif self.status == 'terminal' and self.date_booking is not None:
            self.update_status('loading')
        elif self.status == 'loading' and self.data_container is not None:
            self.update_status('shipped')
        elif self.status == 'shipped' and self.date_unloaded is not None:
            self.update_status('unloaded')
        elif self.status == 'unloaded' and self.date_unloaded is not None and (
                timezone.now().date() - self.date_unloaded) > timedelta(days=30):
            self.update_status('archived')

    def save(self, *args, **kwargs):
        if all([self.auto, self.vin, self.lot, self.account, self.auction, self.customer, self.price,
                self.date_purchase,
                self.bos]):
            self.status_changed = timezone.now()
        self.move_to_next_status()
        super().save(*args, **kwargs)

    def get_wait_days(self):
        if self.status in ['new', 'dispatched', 'terminal', 'loading', 'shipped', 'unloaded']:
            return (timezone.now() - self.status_changed).days + 1
        return 1

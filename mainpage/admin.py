from django.contrib import admin
from .models import *

# Register your models here.
admin.site.register(Project)
admin.site.register(ProjectMapType)
admin.site.register(ProjectFile)
admin.site.register(ProjectVote)

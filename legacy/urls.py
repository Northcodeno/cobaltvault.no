from django.conf import settings
from django.conf.urls import url
from django.conf.urls.static import static

from . import views

urlpatterns = [
    url(r'^project/(?P<project_id>[0-9A-Za-z-]+)/(?P<project_action>[A-Za-z]+)/$', views.legacy_project, name='legacy_edit'),
    url(r'^project/(?P<project_id>[0-9A-Za-z-]+)/$', views.legacy_project, name='legacy_project'),
    url(r'^register\.php$', views.legacy_register, name='legacy_register'),
    url(r'^user/(?P<user_id>[0-9A-Za-z-]+)/$', views.legacy_profile, name='legacy_profile'),
]

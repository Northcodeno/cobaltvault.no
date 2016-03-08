from django.conf.urls import url
from django.conf.urls.static import static
from django.conf import settings

from . import views

urlpatterns = [
    url(r'^$', views.index, name="index"),
    url(r'^list$', views.list, name="list"),
    url(r'^p/(?P<project_id>[0-9A-Za-z-]+)/download$', views.project_download, name='project_download'),
    url(r'^p/(?P<project_id>[0-9A-Za-z-]+)/$', views.project, name='project'),
    url(r'^login$', views.login_view, name='login'),
    url(r'^logout$', views.logout_view, name='logout'),
    url(r'^register$', views.register_view, name='register'),
    url(r'^activate/(?P<activation_id>[0-9A-Za-z-]+)/$', views.activate, name='activate')
]

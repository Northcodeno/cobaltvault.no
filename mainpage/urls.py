from django.conf.urls import url
from . import views

urlpatterns = [
    url(r'^$', views.index, name="index"),
    url(r'^p/(?P<project_id>[0-9A-Za-z]+)/$', views.project, name='project'),
]

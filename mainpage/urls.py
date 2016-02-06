from django.conf.urls import url
from django.conf.urls.static import static
from django.conf import settings

from . import views

urlpatterns = [
    url(r'^$', views.index, name="index"),
    url(r'^p/(?P<project_id>[0-9A-Za-z]+)/download$', views.project_download, name='project_download'),
    url(r'^p/(?P<project_id>[0-9A-Za-z]+)/$', views.project, name='project'),
] + static(settings.THUMBNAIL_URL, document_root=settings.THUMBNAIL_ROOT)
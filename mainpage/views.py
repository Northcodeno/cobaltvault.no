from django.shortcuts import render
from django.http import Http404
from django.views.generic.list import ListView
from django.utils.encoding import smart_str
from django.db.models import Q
from django.shortcuts import get_object_or_404
from django_tables2 import RequestConfig

from .models import Project
from .tables import ProjectTable
from info.models import NewsPost

# Create your views here.
def index(request):
	latest = Project.objects.order_by('-date_modified')[:5]
	mostdl = Project.objects.order_by('-downloads')[:5]
	news = NewsPost.objects.all()[:5]
	return render(request, "mainpage/index.html", { 'latest': latest, 'mostdl': mostdl, 'news': news })

def list(request):
	table = ProjectTable(Project.objects.all())
	table.paginate(page=request.GET.get('page',1), per_page=25)
	RequestConfig(request).configure(table)
	return render(request, "mainpage/list.html", {'table': table })

def project(request, project_id):
	if (project_id.isdigit()):
		project = get_object_or_404(Project, pk=project_id)
	else:
		project = get_object_or_404(Project, idname=project_id)
	return render(request, "mainpage/project.html", {'project': project})

def project_download(request, project_id):
	return
	#project = get_object_or_404(Project, Q(pk=project_id)|Q(idname=project_id))
	
	#response = HttpResponse(mimetype='application/force-download')
	#response['Content-Disposition'] = 'attachment; filename=%s' % smart_str()

from django.shortcuts import render
from django.http import Http404
from django.views.generic.list import ListView
from django.utils.encoding import smart_str
from django.db.models import Q
from django.shortcuts import get_object_or_404

from .models import Project

# Create your views here.
def index(request):
	latest = Project.objects.order_by('date_created')[:5]
	mostdl = Project.objects.order_by('downloads')[:5]
	featured = Project.objects.order_by('?')[:5]
	return render(request, "mainpage/index.html", { 'latest': latest, 'mostdl': mostdl, 'featured': featured })

def list(request):
	return render(request, "mainpage/list.html", {'projects': Project.objects.all() })

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
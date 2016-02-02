from django.shortcuts import render
from django.http import Http404
from django.views.generic.list import ListView
from django.utils.encoding import smart_str

from .models import Project

# Create your views here.
def index(request):
    return render(request, "mainpage/index.html", {})

def project(request, project_id):
	try:
		project = Project.objects.get(pk=project_id)
	except:
		try:
			project = Project.objects.get(idname=project_id)
		except Project.DoesNotExist:
			raise Http404('Project does not exist!')
	return render(request, "mainpage/project.html", {'project': project})

def project_download(request, project_id):
	try:
		project = Project.objects.get(pk=project_id)
	except:
		try:
			project = Project.objects.get(idname=project_id)
		except Project.DoesNotExist:
			raise Http404('Project does not exist!')
	
	#response = HttpResponse(mimetype='application/force-download')
	#response['Content-Disposition'] = 'attachment; filename=%s' % smart_str()
import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:lp1_ciasie_atelier_2/class/custom_exception.dart';
import 'package:lp1_ciasie_atelier_2/class/event.dart';
import 'package:lp1_ciasie_atelier_2/class/location.dart';
import 'package:lp1_ciasie_atelier_2/class/user.dart';
import 'package:lp1_ciasie_atelier_2/provider/session_provider.dart';
import 'package:lp1_ciasie_atelier_2/screen/auth/sign_in_screen.dart';
import 'package:lp1_ciasie_atelier_2/screen/event/event_edit_builder_screen.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:latlong2/latlong.dart';
import 'package:provider/provider.dart'; 
import 'package:http/http.dart' as http;
class EventPage extends StatefulWidget {
final Event event;

const EventPage({
Key? key,
required this.event,
}) : super(key: key);

@override
State<EventPage> createState() => _EventPageState();
}

class _EventPageState extends State<EventPage> {
  final TextEditingController _filterController = TextEditingController();

  @override
  void initState() {
    super.initState();
  }

  _openDialogShareEvent() {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: TextField(
            controller: _filterController,
            decoration: const InputDecoration(
              border: OutlineInputBorder(),
              labelText: 'Rechercher un ami',
            ),
          ),
          content: Container(
            child: ListView(
              children: [
                CheckboxListTile(
                  value: false,
                  onChanged: (o) => {},
                  title: Text('temp'),
                ),
                CheckboxListTile(
                  value: false,
                  onChanged: (o) => {},
                  title: Text('temp'),
                ),
                CheckboxListTile(
                  value: false,
                  onChanged: (o) => {},
                  title: Text('temp'),
                ),
                CheckboxListTile(
                  value: false,
                  onChanged: (o) => {},
                  title: Text('temp'),
                ),
              ],
            ),
          ),
          actions: [
            TextButton(
              // textColor: Color(0xFF6200EE),
              onPressed: () {},
              child: const Text('CANCEL'),
            ),
            TextButton(
              // textColor: Color(0xFF6200EE),
              onPressed: () {},
              child: const Text('ACCEPT'),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        actions: [
          IconButton(
            onPressed: () {
              Navigator.push(
                context,
                MaterialPageRoute(
                  builder: (context) => EventEditBuilderPage(
                    idEvent: widget.event.idEvent,
                  ),
                ),
              );
            },
            icon: const Icon(Icons.edit_outlined),
          ),
          IconButton(
            onPressed: () {},
            icon: const Icon(Icons.delete_outlined),
          ),
        ],
      ),
      body: Padding(
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 12),
              child: Text(widget.event.title,
                  style: const TextStyle(fontSize: 24.6)),
            ),
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 12),
              child: Text(
                  '${DateFormat('dd/MM/yyyy').format(widget.event.date)} à ${widget.event.hour.hour.toString().padLeft(2, '0')}:${widget.event.hour.minute.toString().padLeft(2, '0')}',
                  style: const TextStyle(fontSize: 19.6)),
            ),
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 12),
              child: Text(
                widget.event.description,
              ),
            ),
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 12),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text(
                    'Participants',
                    style: TextStyle(fontSize: 19.6),
                  ),
                  OutlinedButton.icon(
                    onPressed: () => {_openDialogShareEvent()},
                    icon: const Icon(Icons.person_add_outlined),
                    label: const Text('AJOUTER'),
                  )
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
rethrow;
}
}

@override
Widget build(BuildContext context) {
return Scaffold(
appBar: AppBar(
actions: [
IconButton(
onPressed: () {
Navigator.push(
context,
MaterialPageRoute(
builder: (context) => EventEditBuilderPage(
idEvent: widget.event.idEvent,
),
),
);
},
icon: const Icon(Icons.edit_outlined),
),
IconButton(
onPressed: () {},
icon: const Icon(Icons.delete_outlined),
),
],
),
body: Padding(
padding: const EdgeInsets.all(12),
child: Column(
crossAxisAlignment: CrossAxisAlignment.stretch,
children: [
Padding(
padding: const EdgeInsets.symmetric(vertical: 12),
child: Text(widget.event.title,
style: const TextStyle(fontSize: 24.6)),
),
Padding(
padding: const EdgeInsets.symmetric(vertical: 12),
child: Text(
'${DateFormat('dd/MM/yyyy').format(widget.event.date)} à ${widget.event.hour.hour.toString().padLeft(2, '0')}:${widget.event.hour.minute.toString().padLeft(2, '0')}',
style: const TextStyle(fontSize: 19.6)),
),
Padding(
padding: const EdgeInsets.symmetric(vertical: 12),
child: Text(
widget.event.description,
),
),
Padding(padding: const EdgeInsets.symmetric(vertical: 12) , child: FutureBuilder(
future: fetchEventLocation(context),

builder: (context, snapshot) {
if (snapshot.hasError) {
return Text(snapshot.error.toString());
} else if (snapshot.hasData) {

return FlutterMap(
options: MapOptions(
center: LatLng(
snapshot.data!.lat ,
snapshot.data!.long,
),
zoom: 13.0,
),
nonRotatedChildren: [
AttributionWidget.defaultWidget(
source: 'OpenStreetMap contributors',
onSourceTapped: null,
),
],

children: [

TileLayer(
urlTemplate:
'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
userAgentPackageName: 'com.example.app',
),
MarkerLayer(
markers: [
Marker(
width: 80.0,
height: 80.0,
point: LatLng(
snapshot.data!.lat ,
snapshot.data!.long,),
builder: (ctx) => const Icon(Icons.location_on),
),
],
),
],
);
} else {
return const CircularProgressIndicator();
}
},
),
),
Padding(
padding: const EdgeInsets.symmetric(vertical: 12),
child: Row(
mainAxisAlignment: MainAxisAlignment.spaceBetween,
children: [
const Text(
'Participants',
style: TextStyle(fontSize: 19.6),
),
OutlinedButton.icon(
onPressed: () => {},
icon: const Icon(Icons.add_outlined),
label: const Text('AJOUTER'),
)
],
),
),
]
),

)
);
}
}

 



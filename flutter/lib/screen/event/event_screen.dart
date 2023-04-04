
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:lp1_ciasie_atelier_2/class/event.dart';
import 'package:lp1_ciasie_atelier_2/class/location.dart';
import 'package:lp1_ciasie_atelier_2/screen/event/event_edit_builder_screen.dart';
import 'package:flutter_map/flutter_map.dart';
import 'package:latlong2/latlong.dart'; 

class EventPage extends StatefulWidget {
  final Event event;
  final Location location;

  const EventPage({
    Key? key,
    required this.event,
    required this.location,
  }) : super(key: key);

  @override
  State<EventPage> createState() => _EventPageState();
}

class _EventPageState extends State<EventPage> {
  @override
  void initState() {
    super.initState();
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
                      onPressed: () => {},
                      icon: const Icon(Icons.add_outlined),
                      label: const Text('AJOUTER'),
                    )
                  ],
                )),
                Padding(
                  padding: const EdgeInsets.symmetric(vertical: 12),
                child:FlutterMap(
                options: MapOptions(
                  center: LatLng(
                      
                           widget.location.lat 
                              ? 90
                              : widget.location.latitude,
                          ? 90
                          : widget.fruit.origin.location.coordinates[1]),
                  zoom: 9.2,
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
                            widget.fruit.origin.location.coordinates[0] > 90
                                ? 90
                                : widget.fruit.origin.location.coordinates[0],
                            widget.fruit.origin.location.coordinates[1] < -90
                                ? 90
                                : widget.fruit.origin.location.coordinates[1]),
                        builder: (ctx) => const Icon(Icons.location_on),
                      ),
                    ],
                  ),
                ],
              ),
                ),
                
          ],
        ),
      ),
    );
  }
}

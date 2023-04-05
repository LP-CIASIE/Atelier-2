class Location {
final String idLocation;
final String name;
final double lat;
final double long;
final bool isRelated;

Location({
required this.idLocation,
required this.name,
required this.lat,
required this.long,
required this.isRelated,
});

factory Location.fromMap(Map map) {
return Location(
idLocation: map['id'],
name: map['name'],
lat: map['lat'],
long: map['long'],
isRelated: map['is_related'] == 1 ? true : false,
);
}
}